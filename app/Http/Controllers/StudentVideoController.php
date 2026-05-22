<?php
// app/Http/Controllers/StudentVideoController.php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Video;
use App\Models\Certificate;
use App\Models\StudentVideoProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentVideoController extends Controller
{
    public function markVideoCompleted(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'course_id' => 'required|exists:courses,id',
        ]);
        
        $student = Auth::user()->student;
        
        // تسجيل إكمال الفيديو
        $student->markVideoCompleted($request->video_id, $request->course_id);
        
        // التحقق من إكمال جميع الفيديوهات
        $progress = $student->getCourseProgress($request->course_id);
        
        $response = [
            'success' => true,
            'course_completed' => false
        ];
        
        // إذا أكمل الطالب جميع الفيديوهات ولم يحصل على شهادة بعد
        if ($progress['is_completed'] && !$student->hasCertificateForCourse($request->course_id)) {
            $certificate = $this->createCertificate($student->id, $request->course_id);
            $response['course_completed'] = true;
            $response['certificate_url'] = route('certificate.show', $certificate->id);
        }
        
        return response()->json($response);
    }
    
    private function createCertificate($studentId, $courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // إنشاء رقم شهادة فريد
        $certificateNumber = 'CE-' . date('Y') . '-' . $courseId . '-' . $studentId . '-' . strtoupper(substr(uniqid(), -6));
        
        return Certificate::create([
            'student_id' => $studentId,
            'course_id' => $courseId,
            'instructor_id' => $course->instructor_id,
            'certificate_number' => $certificateNumber,
            'issued_date' => now(),
        ]);
    }
}