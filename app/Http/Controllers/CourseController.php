<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Category;
use App\Models\Instructor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

// استيراد مكتبة chillerlan/php-qrcode
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class CourseController extends Controller
{
    // عرض قائمة الكورسات (لم يتم تغيير هذه الدالة)
    // public function index(Request $request)
    // {
    //     $user = null;
    //     $isInstructor = false;
    //     $isAdmin = false;
    //     $isStudent = false;

    //     if (auth('admin')->check()) {
    //         $user = auth('admin')->user();
    //         $isAdmin = true;
    //     } elseif (auth('instructor')->check()) {
    //         $user = auth('instructor')->user();
    //         $isInstructor = true;
    //     } elseif (auth('student')->check()) {
    //         $user = auth('student')->user();
    //         $isStudent = true;
    //     }

    //     $query = Course::with(['instructor.user1', 'category'])->withCount('students');

    //     if ($isInstructor && $user) {
    //         $instructorId = $user->actor_id;
    //         $query->where('instructor_id', $instructorId);
    //     }

    //     if ($isStudent && $user) {
    //         $studentId = $user->actor_id;
    //         $query->whereHas('students', function($q) use ($studentId) {
    //             $q->where('student_id', $studentId);
    //         });
    //     }

    //     if ($request->has('category_id') && $request->category_id != '') {
    //         $query->where('category_id', $request->category_id);
    //     }

    //     if ($request->has('search') && $request->search != '') {
    //         $search = $request->search;
    //         $query->where(function($q) use ($search) {
    //             $q->where('course_name', 'like', '%' . $search . '%')
    //               ->orWhereHas('instructor.user1', function($q2) use ($search) {
    //                   $q2->where('username', 'like', '%' . $search . '%');
    //               });
    //         });
    //     }

    //     if ($isInstructor) {
    //         $totalCourses = (clone $query)->count();
    //         $activeCourses = (clone $query)->where('status', 'active')->count();
    //         $totalInstructors = Instructor::count();
    //     } elseif ($isStudent) {
    //         $totalCourses = (clone $query)->count();
    //         $activeCourses = (clone $query)->where('status', 'active')->count();
    //         $totalInstructors = Instructor::count();
    //     } else {
    //         $totalCourses = Course::count();
    //         $activeCourses = Course::where('status', 'active')->count();
    //         $totalInstructors = Instructor::count();
    //     }

    //     $courses = $query->latest()->paginate(10)->appends($request->query());

    //     return view('cms.course.index', compact(
    //         'courses',
    //         'totalCourses',
    //         'activeCourses',
    //         'totalInstructors'
    //     ));
    // }

    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user && ($user->role == 'admin' || $user->role == 'super admin');
        $isInstructor = $user && $user->role == 'instructor';
        $isStudent = $user && $user->role == 'student';

        $query = Course::with(['instructor.user1', 'category'])->withCount('students');

        if ($isInstructor && $user) {
            $instructorId = $user->actor_id;
            $query->where('instructor_id', $instructorId);
        }

        if ($isStudent && $user) {
            $studentId = $user->actor_id;
            $query->whereHas('students', function($q) use ($studentId) {
                $q->where('student_id', $studentId);
            });
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('course_name', 'like', '%' . $search . '%')
                  ->orWhereHas('instructor.user1', function($q2) use ($search) {
                      $q2->where('username', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($isInstructor && $user) {
            $totalCourses = (clone $query)->count();
            $activeCourses = (clone $query)->where('status', 'active')->count();
            $totalInstructors = Instructor::count();
        } elseif ($isStudent && $user) {
            $totalCourses = (clone $query)->count();
            $activeCourses = (clone $query)->where('status', 'active')->count();
            $totalInstructors = Instructor::count();
        } else {
            $totalCourses = Course::count();
            $activeCourses = Course::where('status', 'active')->count();
            $totalInstructors = Instructor::count();
        }

        $courses = $query->latest()->paginate(10)->appends($request->query());

        return view('cms.course.index', compact(
            'courses',
            'totalCourses',
            'activeCourses',
            'totalInstructors'
        ));
    }

    
    // إنشاء كورس جديد (لم يتغير)
    public function create()
    {
        $categories = Category::all();
        $user = auth()->user();

        if ($user && $user->role == 'instructor') {
            $instructorId = $user->actor_id;
            return view('cms.course.create', compact('categories', 'instructorId'));
        } else {
            $instructors = Instructor::with('user1')->get();
            return view('cms.course.create', compact('categories', 'instructors'));
        }
    }

    // حفظ الكورس (لم يتغير)
    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'course_name'   => 'required|string|min:3|max:100',
            'category_id'   => 'required|exists:categories,id',
            'level'         => 'required|in:beginner,intermediate,advanced',
            'no_hours'      => 'required|numeric|min:1',
            'course_image'  => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'short_description' => 'nullable|string|max:500',
            'status'        => 'nullable|in:active,draft',
        ];

        if ($user && $user->role == 'admin') {
            $rules['instructor_id'] = 'required|exists:instructors,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if (!$validator->fails()) {
            $course = new Course();
            $course->course_name = $request->input('course_name');
            $course->short_description = $request->input('short_description');
            $course->level = $request->input('level');
            $course->no_hours = $request->input('no_hours');
            $course->rating = 0;
            $course->status = $request->input('status', 'active');
            $course->category_id = $request->input('category_id');

            if ($user && $user->role == 'instructor') {
                $course->instructor_id = $user->actor_id;
            } else {
                $course->instructor_id = $request->input('instructor_id');
            }

            if ($request->hasFile('course_image')) {
                $file = $request->file('course_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('courses', $filename, ['disk' => 'public']);
                $course->course_image = 'courses/' . $filename;
            }

            $isSaved = $course->save();

            return response()->json([
                'icon' => $isSaved ? 'success' : 'error',
                'title' => $isSaved ? 'تم حفظ الكورس بنجاح' : 'فشل الحفظ'
            ], $isSaved ? 201 : 400);

        } else {
            return response()->json([
                'icon' => 'error',
                'title' => $validator->getMessageBag()->first()
            ], 400);
        }
    }

    // عرض كورس معين (لم يتغير)
    public function show($id)
    {
        $course = Course::with(['instructor.user1', 'category'])->findOrFail($id);
        return view('cms.course.show', compact('course'));
    }

    // تعديل كورس (لم يتغير)
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $user = auth()->user();

        if ($user && $user->role == 'instructor' && $course->instructor_id != $user->actor_id) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا الكورس');
        }

        $categories = Category::all();

        if ($user && $user->role == 'instructor') {
            return view('cms.course.edit', compact('course', 'categories'));
        } else {
            $instructors = Instructor::with('user1')->get();
            return view('cms.course.edit', compact('course', 'categories', 'instructors'));
        }
    }

    // تحديث كورس (لم يتغير)
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $user = auth()->user();

        if ($user && $user->role == 'instructor' && $course->instructor_id != $user->actor_id) {
            return response()->json([
                'icon' => 'error',
                'title' => 'غير مسموح',
                'text' => 'ليس لديك صلاحية لتعديل هذا الكورس'
            ], 403);
        }

        $rules = [
            'course_name'       => 'required|string|min:3|max:100',
            'category_id'       => 'required|exists:categories,id',
            'no_hours'          => 'required|integer|min:1',
            'level'             => 'required|in:beginner,intermediate,advanced',
            'status'            => 'required|in:active,draft',
            'short_description' => 'nullable|string|max:500',
            'course_image'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ];

        if ($user && $user->role == 'admin') {
            $rules['instructor_id'] = 'required|exists:instructors,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if (!$validator->fails()) {
            $course->course_name = $request->input('course_name');
            $course->category_id = $request->input('category_id');
            $course->no_hours = $request->input('no_hours');
            $course->level = $request->input('level');
            $course->status = $request->input('status');
            $course->short_description = $request->input('short_description');

            if ($user && $user->role == 'admin') {
                $course->instructor_id = $request->input('instructor_id');
            }

            if ($request->hasFile('course_image')) {
                if ($course->course_image && Storage::disk('public')->exists($course->course_image)) {
                    Storage::disk('public')->delete($course->course_image);
                }
                $file = $request->file('course_image');
                $imageName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('courses', $imageName, ['disk' => 'public']);
                $course->course_image = 'courses/' . $imageName;
            }

            $isSaved = $course->save();

            return response()->json([
                'icon' => $isSaved ? 'success' : 'error',
                'title' => $isSaved ? 'تم تحديث الكورس بنجاح' : 'فشل التحديث'
            ], $isSaved ? 200 : 400);

        } else {
            return response()->json([
                'icon' => 'error',
                'title' => $validator->getMessageBag()->first()
            ], 400);
        }
    }

    // حذف كورس (لم يتغير)
    public function destroy($id)
    {
        $course = Course::find($id);
        $user = auth()->user();

        if (!$course) {
            return response()->json([
                'icon' => 'error',
                'title' => 'خطأ!',
                'text' => 'الكورس الذي تحاول حذفه غير موجود.'
            ], 404);
        }

        if ($user && $user->role == 'instructor' && $course->instructor_id != $user->actor_id) {
            return response()->json([
                'icon' => 'error',
                'title' => 'غير مسموح',
                'text' => 'ليس لديك صلاحية لحذف هذا الكورس'
            ], 403);
        }

        $deleted = $course->delete();

        if ($deleted) {
            return response()->json([
                'icon' => 'success',
                'title' => 'تم الحذف بنجاح',
                'text' => 'تمت إزالة الكورس من قاعدة البيانات.'
            ], 200);
        } else {
            return response()->json([
                'icon' => 'error',
                'title' => 'فشل الحذف',
                'text' => 'حدث خطأ ما أثناء محاولة الحذف.'
            ], 400);
        }
    }

    // =================================================================
    // هذه الدالة تعرض تفاصيل الكورس وتحتوي على الـ QR Code الديناميكي
    // =================================================================
public function showCourseDetails($id)
{
    $course = Course::with([
        'lessons',
        'materials',
        'reviews.user',
        'instructor.user1'
    ])->findOrFail($id);

    $instructor = $course->instructor;

    // الأولوية: LinkedIn ← GitHub ← صفحة بروفايل داخلية
    $profileUrl = $instructor->linkedin_url ?? $instructor->github_url ?? url('/instructors/' . $instructor->id);

    // توليد QR Code بلون بنفسجي نيون (#bb86fc) وخلفية داكنة
    $qrCodeUrl = "https://quickchart.io/qr?text=" . urlencode($profileUrl) . "&size=150&margin=2&dark=bb86fc&light=1e1e23";

    return view('course-details', compact('course', 'qrCodeUrl'));
}

    public function showCoursePlayer($id)
    {
        $course = Course::with(['videos', 'materials', 'instructor.user1'])->findOrFail($id);
        $user = auth('student')->user();
        $enrolled = false;
        $progress = 0;

        if ($user) {
            $student = null;
            if ($user->actor_type === 'App\Models\Student') {
                $student = \App\Models\Student::find($user->actor_id);
            } else {
                $student = $user->student;
            }

            if ($student) {
                $enrolled = $course->students()->where('student_id', $student->id)->exists();
                if ($enrolled) {
                    $totalVideos = $course->videos->count();
                    $completedVideos = \App\Models\StudentVideoProgress::where('student_id', $student->id)
                        ->where('course_id', $course->id)
                        ->where('completed', true)
                        ->count();
                    $progress = $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100) : 0;

                    $course->videos->each(function($video) use ($student) {
                        $video->completed_for_user = \App\Models\StudentVideoProgress::where('student_id', $student->id)
                            ->where('video_id', $video->id)
                            ->where('completed', true)
                            ->exists();
                    });
                }
            }
        }

        return view('cms.studentDash.player', compact('course', 'enrolled', 'progress'));
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = auth('student')->id();

        if (!$userId) {
            return back()->with('error', 'يجب تسجيل الدخول كطالب للتقييم');
        }

        \App\Models\Review::create([
            'course_id' => $id,
            'user_id'   => $userId,
            'rating'    => $request->rating,
            'comment'   => $request->comment,
        ]);

        return back()->with('success', 'تم إرسال تقييمك بنجاح!');
    }

    public function home()
    {
        $latestReviews = \App\Models\Review::with(['user', 'course'])->latest()->take(3)->get();
        return view('cms.home.home', compact('latestReviews'));
    }

    public function manageLessons(Course $course)
    {
        $lessons = $course->lessons;
        return view('cms.video.index', compact('course', 'lessons'));
    }

    public function player($id)
    {
        $course = Course::with(['quizzes', 'videos', 'materials'])->findOrFail($id);
        return view('cms.studentDash.player', compact('course'));
    }

    public function verifyInternship(Request $request, $id)
    {
        $request->validate(['access_code' => 'required|string']);

        $user = auth('student')->user();
        $student = $user->student;

        if (!$student) {
            return back()->with('error', 'بيانات الطالب غير موجودة.');
        }

        if ($request->access_code !== '2026') {
            return back()->with('error', 'كود التفعيل غير صحيح!');
        }

        if ($student->courses()->where('course_id', $id)->exists()) {
            return back()->with('info', 'أنت مسجل بالفعل في هذا المسار.');
        }

        $student->courses()->attach($id);

        return redirect()->route('course.player', ['id' => $id]);
    }
}
