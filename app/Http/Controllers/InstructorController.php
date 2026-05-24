<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\User1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class InstructorController extends Controller
{
    public function index()
    {
        $instructors = Instructor::with('user1')->orderBy('id', 'desc')->withoutTrashed()->paginate(10);
        $totalInstructors = Instructor::count();
        $avgRating = round(Instructor::avg('rating'), 1);
        $avgExperience = round(Instructor::avg('experience_years'), 1);

        return view('cms.instructor.index', compact(
            'instructors',
            'totalInstructors',
            'avgRating',
            'avgExperience'
        ));
    }

    public function create()
    {
        $roles = Role::where('guard_name', 'instructor')->get();
        return response()->view('cms.instructor.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator($request->all(), [
            'username' => 'required|string|min:3|max:20|unique:user1s,username',
            'email'    => 'required|email|unique:user1s,email',
            'password' => 'required|min:8|confirmed',

            'specialization'   => 'required|string',
            'experience_years' => 'required|integer|min:0',
            'rating'           => 'required|numeric|min:1|max:5',
            'bio'              => 'nullable|string',
            'enrollment_date'  => 'nullable|date',
            'linkedin_url'     => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'icon'  => 'error',
                'title' => $validator->errors()->first(),
            ], 400);
        }

        try {
            DB::beginTransaction();

            $user1 = User1::create([
                'username'   => $request->username,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'role'       => 'instructor',
                'actor_id'   => 0,
                'actor_type' => 'App\Models\Instructor',
            ]);

            $instructor = Instructor::create([
                'specialization'   => $request->specialization,
                'experience_years' => $request->experience_years,
                'rating'           => $request->rating,
                'bio'              => $request->bio,
                'enrollment_date'  => $request->enrollment_date ?? now(),
                'linkedin_url'     => $request->linkedin_url,
            ]);

            $user1->update(['actor_id' => $instructor->id]);
            $instructor->assignRole('instructor');

            DB::commit();

            return response()->json([
                'icon'  => 'success',
                'title' => 'تم إنشاء المدرب بنجاح'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'icon'  => 'error',
                'title' => 'خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $instructor = Instructor::with('user1')->findOrFail($id);
        return response()->view('cms.instructor.show', compact('instructor'));
    }

    public function edit($id)
    {
        $instructor = Instructor::with('user1')->findOrFail($id);
        return view('cms.instructor.edit', compact('instructor'));
    }

    private function getUser1Id($instructorId)
    {
        $instructor = Instructor::find($instructorId);
        return $instructor ? $instructor->user1->id : 0;
    }

    public function update(Request $request, $id)
    {
        $instructor = Instructor::with('user1')->findOrFail($id);

        $validator = Validator($request->all(), [
            'username' => 'required|string|min:3|max:20|unique:user1s,username,' . $instructor->user1->id,
            'email'    => 'required|email|unique:user1s,email,' . $instructor->user1->id,
            'specialization'   => 'required|string',
            'experience_years' => 'required|integer|min:0',
            'rating'           => 'required|numeric|min:1|max:5',
            'bio'              => 'nullable|string',
            'enrollment_date'  => 'nullable|date',
            'linkedin_url'     => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'icon'  => 'error',
                'title' => $validator->errors()->first(),
            ], 400);
        }

        try {
            $instructor->user1->update([
                'username' => $request->username,
                'email'    => $request->email,
            ]);

            $instructor->update([
                'specialization'   => $request->specialization,
                'experience_years' => $request->experience_years,
                'rating'           => $request->rating,
                'bio'              => $request->bio ?? $instructor->bio,
                'enrollment_date'  => $request->enrollment_date ?? $instructor->enrollment_date,
                'linkedin_url'     => $request->linkedin_url,
            ]);

            return response()->json([
                'icon'  => 'success',
                'title' => 'تم تحديث بيانات المدرس بنجاح'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'icon'  => 'error',
                'title' => 'خطأ في التحديث: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $instructor = Instructor::find($id);
        if (!$instructor) {
            return redirect()->back()->with('error', 'المدرب غير موجود');
        }
        if ($instructor->user1) {
            $instructor->user1->delete();
        }
        $instructor->delete();
        return redirect()->back()->with('success', 'تم حذف المدرب مؤقتاً');
    }

    public function trashed()
    {
        $instructors = Instructor::onlyTrashed()
            ->with(['user1' => function($query) {
                $query->withTrashed();
            }])
            ->orderBy('deleted_at', 'desc')
            ->get();
        return view('cms.instructor.trashed', compact('instructors'));
    }

    public function restore($id)
    {
        try {
            $instructor = Instructor::onlyTrashed()->findOrFail($id);
            $instructor->restore();

            $user1 = User1::where('actor_id', $instructor->id)
                          ->where('actor_type', 'App\Models\Instructor')
                          ->withTrashed()
                          ->first();
            if ($user1 && $user1->trashed()) {
                $user1->restore();
            }
            return redirect()->back()->with('success', 'تم استعادة المدرس والمستخدم بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطأ: ' . $e->getMessage());
        }
    }

    public function force($id)
    {
        $instructor = Instructor::onlyTrashed()->findOrFail($id);
        $instructor->forceDelete();
        return back()->with('success', 'تم الحذف النهائي');
    }

    public function forceAll()
    {
        Instructor::onlyTrashed()->forceDelete();
        return back()->with('success', 'تم حذف جميع المحذوفين نهائياً');
    }
}
