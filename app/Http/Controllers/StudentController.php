<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class StudentController extends Controller
{

    public function index()
    {
        // $this->authorize('viewAny', Admin::class);
        $students = Student::with('user1')->orderBy('id', 'desc')->withoutTrashed()->paginate(10);
        $totalStudents = Student::count();

        $mostCommonLevel = Student::select('level')
            ->groupBy('level')
            ->orderByRaw('COUNT(*) DESC')
            ->first();

        $avgSkillArabic = 'غير محدد';
        if ($mostCommonLevel) {
            $skillsMap = [
                'beginner' => 'مبتدئ',
                'intermediate' => 'متوسط',
                'advanced' => 'متقدم',
                'expert' => 'خبير'
            ];
            $avgSkillArabic = $skillsMap[$mostCommonLevel->level] ?? $mostCommonLevel->level;
        }

        $certCount = $totalStudents * 2;
        $threats = 156;

        return view('cms.student.index', compact(
            'students',
            'totalStudents',
            'avgSkillArabic',
            'certCount',
            'threats'
        ));
    }

    // عرض صفحة إنشاء طالب جديد
    public function create()
    {
        $roles = Role::where('guard_name' , 'student')->get();
        // $this->authorize('create' , Student::class);
        return view('cms.student.create',compact('roles'));
    }

    // حفظ طالب جديد
    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'username' => 'required|string|min:3|max:20|unique:user1s,username',
            'email'    => 'required|email|unique:user1s,email',
            'password' => 'required|min:8|confirmed',
            'level'    => 'nullable|string',
            'status'   => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'icon' => 'error',
                'title' => $validator->errors()->first()
            ], 400);
        }

        try {
            DB::beginTransaction();

            // إنشاء المستخدم
            $user1 = User1::create([
                'username'   => $request->username,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'role'       => 'student',
                'actor_id'   => 0,
                'actor_type' => 'App\Models\Student',
            ]);

            // إنشاء الطالب
            $student = Student::create([
                'user_id'         => $user1->id,
                'level'           => $request->level ?? 'beginner',
                'status'          => $request->status ?? 'active',
                'specialization'  => $request->specialization ?? 'General',
                'progress'        => $request->progress ?? 0,
                'enrollment_date' => $request->enrollment_date ?? now(),
            ]);

            // ربط الطالب بالمستخدم
            $user1->update([
                'actor_id' => $student->id
            ]);

            
            $role = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'student']);
$student->assignRole($role);

            DB::commit();

            return response()->json([
                'icon' => 'success',
                'title' => 'تم التسجيل بنجاح!'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'icon' => 'error',
                'title' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // عرض تفاصيل طالب
    public function show($id)
    {
        $student = Student::withTrashed()->with('user1')->find($id);

        if (!$student) {
            return redirect()->back()->with('error', 'الطالب غير موجود');
        }

        return view('cms.student.show', compact('student'));
    }

    // عرض صفحة تعديل طالب
    public function edit($id)
    {
        $student = Student::with('user1')->find($id);

        if (!$student) {
            return redirect()->back()->with('error', 'الطالب غير موجود');
        }

        return view('cms.student.edit', compact('student'));
    }
    
    // تحديث بيانات طالب
    public function update(Request $request, $id)
    {
        $student = Student::with('user1')->find($id);

        if (!$student) {
            return response()->json([
                'icon'  => 'error',
                'title' => 'الطالب غير موجود'
            ], 404);
        }

        $user1Id = $student->user1 ? $student->user1->id : null;

        $validator = Validator($request->all(), [
            'username' => 'sometimes|required|string|min:3|max:20|unique:user1s,username,' . $user1Id,
            'email'    => 'required|email|unique:user1s,email,' . $user1Id,
            'level'    => 'required|string',
            'status'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'icon'  => 'error',
                'title' => $validator->errors()->first(),
            ], 400);
        }

        try {
            DB::beginTransaction();

            if ($student->user1) {
                // التعديل هنا: تحديث وحفظ صريح لجدول User1 لضمان "سماع" التغييرات
                $userAccount = $student->user1;
                $userAccount->username = $request->username;
                $userAccount->email = $request->email;
                $userAccount->save();
            }

            $student->update([
                'level'           => $request->level,
                'status'          => $request->status,
                'specialization'  => $request->specialization ?? $student->specialization,
                'progress'        => $request->progress ?? $student->progress,
                'enrollment_date' => $request->enrollment_date ?? $student->enrollment_date,
            ]);

            DB::commit();

            return response()->json([
                'icon'  => 'success',
                'title' => 'تم تحديث البيانات بنجاح'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'icon'  => 'error',
                'title' => 'خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    // حذف مؤقت (نقل إلى سلة المحذوفات)
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return redirect()->back()->with('error', 'الطالب غير موجود');
        }

        if ($student->user1) {
            $student->user1->delete();
        }

        $student->delete();

        return redirect()->back()->with('success', 'تم حذف الطالب مؤقتاً');
    }

    public function trashed()
    {
        $students = Student::onlyTrashed()
            ->with(['user1' => function($query) {
                $query->withTrashed();  // جلب user1 المحذوف أيضاً
            }])
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('cms.student.trashed', compact('students'));
    }
    
    // استعادة طالب من سلة المحذوفات
    public function restore($id)
    {
        try {
            // البحث عن الطالب المحذوف
            
            $student = Student::onlyTrashed()->findOrFail($id);

            // استعادة الطالب
            $student->restore();

            $user1 = User1::where('actor_id', $student->id)
                          ->where('actor_type', 'App\Models\Student')
                          ->withTrashed()
                          ->first();

            if ($user1 && $user1->trashed()) {
                $user1->restore();
            }

            return redirect()->back()->with('success', 'تم استعادة الطالب والمستخدم بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطأ في الاستعادة: ' . $e->getMessage());
        }
    }

    // حذف نهائي لطالب واحد
    public function force($id)
    {
        $students = Student::onlyTrashed()->findOrFail($id)->forceDelete();
        return back()->with('sucess','sucess');
    }

    public function forceAll()
    {
        $students = Student::onlyTrashed()->forceDelete();
        return back()->with('sucess','sucess');
    }
}