<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Course;
use App\Models\Instructor;
use Spatie\Permission\Models\Role;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User1;
use Spatie\Permission\Traits\HasRoles;


use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * عرض قائمة المسؤولين
     */
    public function index()
    {
        // $this->authorize('viewAny', Admin::class);
        $admins = Admin::with('user1')->orderBy('id', 'desc')->paginate(10);
        return response()->view('cms.admin.index', compact('admins'));
    }

    /**
     * الصفحة الرئيسية للداشبورد (الإحصائيات)
     */
   public function main()
    {
        // المتغيرات المشتركة لجميع الأدوار
        $totalUsers = User1::count();
        $totalCourses = Course::count();
        $newStudents = Student::with('user1')->latest()->limit(10)->get();
        $weeklyRegistrations = $this->getWeeklyRegistrations();
        $monthlyRegistrations = $this->getMonthlyRegistrations();
         $activeCourses = Course::where('status', 'active')->count();

        // متغير الكورسات (سيتم تعبئته حسب الدور)
        $courses = [];

        // فحص الطالب
        if (auth('student')->check()) {
            $user = auth('student')->user();
            $student = $user->actor;

            $enrolledCourses = $student->courses()->with(['instructor.user1'])->get();
            $certificatesCount = \App\Models\Certificate::where('student_id', $student->id)->count();

            $coursesProgress = [];
            foreach ($enrolledCourses as $course) {
                $coursesProgress[$course->id] = $student->getCourseProgress($course->id);
            }


            $courses = $enrolledCourses;

            return view('cms.admin.main', compact(
                'user', 'student', 'enrolledCourses', 'certificatesCount', 'coursesProgress',
                'totalUsers', 'totalCourses', 'newStudents', 'weeklyRegistrations', 'monthlyRegistrations',
                'courses','activeCourses'
            ));
        }

        // فحص المدرب

      if (auth('instructor')->check()) {
    $user = auth('instructor')->user();
    $instructor = $user->actor;

    if ($instructor) {
        $instructorCourses = \App\Models\Course::where('instructor_id', $instructor->id)
            ->get();

        // إجمالي الساعات
        $totalHours = $instructorCourses->sum('no_hours');
    }

    return view('cms.admin.main', compact(
        'user', 'instructor', 'instructorCourses', 'totalHours',
        'totalUsers', 'totalCourses', 'newStudents', 'weeklyRegistrations', 'monthlyRegistrations'
    ));
}

        // فحص الأدمن
      // فحص الأدمن
if (auth('admin')->check()) {
    $user = auth('admin')->user();
    $courses = Course::with(['instructor.user1', 'category'])->withCount('students')->get();

    return view('cms.admin.main', compact(
        'newStudents', 'courses', 'totalUsers', 'totalCourses',
        'weeklyRegistrations', 'monthlyRegistrations', 'user',
        'activeCourses'    // أضيفي هذا
    ));
}

return redirect()->route('view.login', ['guard' => 'admin']);    }

    private function getWeeklyRegistrations()
    {
        $data = ['students' => [], 'instructors' => [], 'admins' => []];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $this->getArabicDayName($date->dayOfWeek);
            $data['students'][] = User1::whereDate('created_at', $date->toDateString())->where('role', 'student')->count();
            $data['instructors'][] = User1::whereDate('created_at', $date->toDateString())->where('role', 'instructor')->count();
            $data['admins'][] = User1::whereDate('created_at', $date->toDateString())->where('role', 'admin')->count();
        }
        return ['labels' => $labels, 'students' => $data['students'], 'instructors' => $data['instructors'], 'admins' => $data['admins']];
    }

    private function getMonthlyRegistrations()
    {
        $months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
        $data = ['students' => [], 'instructors' => [], 'admins' => []];
        for ($i = 1; $i <= 12; $i++) {
            $data['students'][] = User1::whereMonth('created_at', $i)->whereYear('created_at', date('Y'))->where('role', 'student')->count();
            $data['instructors'][] = User1::whereMonth('created_at', $i)->whereYear('created_at', date('Y'))->where('role', 'instructor')->count();
            $data['admins'][] = User1::whereMonth('created_at', $i)->whereYear('created_at', date('Y'))->where('role', 'admin')->count();
        }
        return ['labels' => $months, 'students' => $data['students'], 'instructors' => $data['instructors'], 'admins' => $data['admins']];
    }

    private function getArabicDayName($dayOfWeek)
    {
        $days = [1 => 'الإثنين', 2 => 'الثلاثاء', 3 => 'الأربعاء', 4 => 'الخميس', 5 => 'الجمعة', 6 => 'السبت', 0 => 'الأحد', 7 => 'الأحد'];
        return $days[$dayOfWeek] ?? 'غير محدد';
    }

    /**
     * عرض صفحة إنشاء مسؤول جديد
     */
    public function create()
    {

        // $this->authorize('create', Admin::class);
        $roles = Role::where('guard_name', 'admin')->get();
        return response()->view('cms.admin.create', compact('roles'));
    }

    /**
     * حفظ مسؤول جديد
     */
    public function store(Request $request)
    {
        // $this->authorize('create', Admin::class);

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:20|unique:user1s,username',
            'email'    => 'required|email|unique:user1s,email',
            'password' => 'required|min:8|confirmed',
            'role_id'  => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['icon' => 'error', 'title' => $validator->errors()->first()], 400);
        }

        try {

            $admin = Admin::create([

            ]);

            // 1. إنشاء Admin (بدون أي حقول)
            $admin = Admin::create();

            // 2. إنشاء User1 مرتبط بـ Admin
            // إنشاء Admin
            $admin = Admin::create();

            // إنشاء User1 مرتبط بـ Admin
            DB::beginTransaction();

            // 1. إنشاء سجل الأدمن
            $admin = Admin::create();

            // 2. إنشاء مستخدم مرتبط بالأدمن
            $user1 = User1::create([
                'username'   => $request->username,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'role'       => 'Admin',
                'guard_name' => 'web',
                'actor_type' => 'App\Models\Admin',
                'actor_id'   => $admin->id,
            ]);






            $roles = Role::findOrFail($request->get('role_id'));
            $admin ->assignRole($roles->name);


            // 3. تعيين الدور للمستخدم

            // تعيين الدور للمستخدم
            $role = Role::findOrFail($request->role_id);
            $user1->assignRole($role->name);

            return response()->json([
                'icon'  => 'success',
                'title' => 'تم إنشاء المسؤول بنجاح'
            ], 200);

            // 3. تعيين الدور (Role)
            $role = Role::findById($request->role_id, 'admin');
            $user1->assignRole($role->name);

            DB::commit();

            return response()->json(['icon' => 'success', 'title' => 'تم إنشاء المسؤول بنجاح'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['icon' => 'error', 'title' => 'خطأ: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        // $this->authorize('delete', Admin::class);
        Admin::destroy($id);
        return response()->json(['icon' => 'success', 'title' => 'تم الحذف بنجاح'], 200);
    }

    // --- وظائف استرجاع الطلاب (Trashed) ---
    public function trashed()
    {
        $students = Student::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        return response()->view('cms.student.trashed', compact('students'));
    }

    public function restore($id)
    {
        Student::onlyTrashed()->findOrFail($id)->restore();
        return back();
    }

    public function force($id)
    {
        Student::onlyTrashed()->findOrFail($id)->forceDelete();
        return back();
    }

    public function forceAll()
    {
        $students = Student::onlyTrashed()->forceDelete();
        return back()->withFragment('success',"success");

    }
}
