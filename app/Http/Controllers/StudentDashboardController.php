<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Certificate;
use App\Models\User1;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $student = null;
        if ($user->actor_type === 'App\Models\Student') {
            $student = Student::find($user->actor_id);
        }

        // إذا ما في طالب
        if (!$student) {
            return redirect('/cms/student/login')->with('error', 'لم يتم العثور على بيانات الطالب');
        }

        // الكورسات المسجل فيها الطالب (نشطة فقط)
        $enrolledCourses = $student->courses()
            ->where('status', 'active')
            ->with(['instructor.user1'])
            ->get();

        // جميع الكورسات المتاحة (نشطة فقط)
        $allCourses = Course::where('status', 'active')->with('instructor')->get();

        // عدد الشهادات
        $certificatesCount = Certificate::where('student_id', $student->id)->count();

        // الشهادات
        $certificates = Certificate::where('student_id', $student->id)
            ->with('course', 'instructor')
            ->latest()
            ->get();

        // تقدم الطالب في كل كورس
        $coursesProgress = [];
        foreach ($enrolledCourses as $course) {
            $progress = $this->getCourseProgress($student->id, $course->id);
            $coursesProgress[$course->id] = $progress;
        }

        return view('cms.studentDash.dashboard', compact(

            'student',
            'user',
            'enrolledCourses',
            'allCourses',
            'certificatesCount',
            'certificates',
            'coursesProgress'
        ));
    }



    private function getCourseProgress($studentId, $courseId)
    {
        $totalVideos = \App\Models\Video::where('course_id', $courseId)->count();
        $completedVideos = \App\Models\StudentVideoProgress::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('completed', true)
            ->count();

        return [
            'total' => $totalVideos,
            'completed' => $completedVideos,
            'percentage' => $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100) : 0,
            'is_completed' => $totalVideos > 0 && $completedVideos >= $totalVideos
        ];
    }

    public function enroll(Request $request)
    {
        $user = Auth::user();

        // الحصول على الطالب
        if ($user->actor_type === 'App\Models\Student') {
            $student = Student::find($user->actor_id);
            $courseId = $request->course_id;
            // يمنع التسجيل في كورس غير نشط
            $course = Course::find($courseId);
            if ($course && $course->status == 'active') {
                $student->courses()->attach($courseId);
            } else {
                return redirect()->back()->with('error', 'لا يمكن التسجيل في كورس غير نشط');
            }
        }

        return redirect()->back()->with('success', 'تم التسجيل في الكورس بنجاح');
    }

    public function myCourses()
    {
        $user = Auth::user();//////1111111111111

        if ($user->actor_type === 'App\Models\Student') {
            $student = Student::find($user->actor_id);
            $courses = $student->courses()
                ->where('status', 'active')
                ->with('instructor')
                ->get();
            return view('cms.studentDash.my-courses', compact('courses'));
        }

        return redirect('/cms/studentDash/dashboard');
    }

    public function myCertificates()
    {
        $user = Auth::user(); ///11111111111

        if ($user->actor_type === 'App\Models\Student') {
            $student = Student::find($user->actor_id);
            $certificates = Certificate::where('student_id', $student->id)
                ->with('course', 'instructor')
                ->latest()
                ->get();

            return view('cms.certificates.show', compact('certificates'));
        }

        return redirect('/cms/studentDash/dashboard');
    }
    /**
 * صفحة CTF للطالب
 */

/**
 * عرض صفحة CTF
 */
public function ctf()
{
    $challenges = [
        ['id' => 1, 'title' => 'شفرة القيصر', 'points' => 50],
        ['id' => 2, 'title' => 'أسرار المطور', 'points' => 70],
        ['id' => 3, 'title' => 'هجوم النص الواضح', 'points' => 100],
    ];
    
    return view('cms.student.ctf', compact('challenges'));
}

/**
 * التحقق من صحة الفلاج
 */
/**
 * التحقق من صحة الفلاج
 */
public function submitFlag(Request $request)
{
    // للتجربة - شوف إذا كان الطلب يوصل
    \Log::info('CTF Request:', [
        'challenge_id' => $request->challenge_id,
        'flag' => $request->flag
    ]);
    
    $challengeId = $request->challenge_id;
    $submittedFlag = $request->flag;
    
    // الأعلام الصحيحة لكل تحدي
    $correctFlags = [
        1 => 'CyberEye{Cry_v1_ph3re}',
        2 => 'CyberEye{1nsp3ct_3l3m3nt_iS_c00l}',
        3 => 'CyberEye{admin_SuP3r_S3cur3_P4ss}',
    ];
    
    // التحقق من صحة الفلاج
    if (isset($correctFlags[$challengeId])) {
        if ($submittedFlag === $correctFlags[$challengeId]) {
            return response()->json([
                'correct' => true,
                'message' => '🎉 تهانينا! الفلاج صحيح. لقد حصلت على النقاط!'
            ]);
        } else {
            return response()->json([
                'correct' => false,
                'message' => '❌ الفلاج غير صحيح. حاول مرة أخرى!'
            ]);
        }
    }
    
    return response()->json([
        'correct' => false,
        'message' => '⚠️ التحدي غير موجود'
    ]);
}
}
