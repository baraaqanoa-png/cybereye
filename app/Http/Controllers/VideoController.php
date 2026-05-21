<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Video;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentVideoProgress;
use App\Models\Certificate;
use Illuminate\Support\Str;

class VideoController extends Controller
{

    public function index(Request $request)
    {
        $courseId = $request->query('course_id');
        $videos = Video::where('course_id', $courseId)
                       ->orderBy('order_number', 'asc')
                       ->get();
        return view('cms.Video.index', compact('videos', 'courseId'));
    }

    public function player($courseId)
    {
        $query = Video::orderBy('created_at', 'asc');

        if ($courseId) {
            $query->where('course_id', $courseId);
        }

        $videos = $query->get();
        $course = $courseId ? Course::find($courseId) : null;

        return view('cms.Video.player', compact('videos', 'course', 'courseId'));
    }

    public function create()
    {
        $lessons = Lesson::all();
        return view('cms.video.create', compact('lessons'));
    }

    public function store(Request $request)
    {
        if (auth()->check() && auth()->user()->role == 'Admin') {
            abort(403, 'غير مسموح للأدمن بإضافة فيديوهات');
        }

        // تم تحويل القواعد إلى مصفوفة [] لحل مشكلة تعارض الـ regex والـ delimiter
        $validator = Validator::make($request->all(), [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'duration'     => ['nullable', 'integer'],
            'url'          => ['nullable', 'file', 'mimes:mp4,mkv,avi,mov', 'max:102400'],
            'youtube_url'  => ['nullable', 'url', 'regex:/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/i'],
            'lesson_id'    => ['nullable', 'exists:lessons,id'],
            'course_id'    => ['required', 'exists:courses,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        $data = $validator->validated();
        $data['duration'] = $request->duration ?? 0;

        $hasFile = $request->hasFile('url');
        $hasYoutube = !empty($request->youtube_url);

        if (!$hasFile && !$hasYoutube) {
            return response()->json([
                'message' => 'يرجى إما رفع ملف فيديو أو إدخال رابط يوتيوب'
            ], 422);
        }

        if ($hasFile) {
            $path = $request->file('url')->store('videos', 'public');
            $data['url'] = 'storage/' . $path;
            $data['youtube_url'] = null;
        }

        if ($hasYoutube) {
            $data['youtube_url'] = $this->convertToEmbedUrl($request->youtube_url);
            $data['url'] = null;
        }

        $video = Video::create($data);

        return response()->json([
            'success' => true,
            'video' => $video
        ]);
    }

    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        // تم تحويل قواعد التحديث إلى مصفوفة أيضاً لمنع الخطأ
        $validator = Validator::make($request->all(), [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'duration'     => ['nullable', 'integer'],
            'url'          => ['nullable', 'file', 'mimes:mp4,mkv,avi,mov', 'max:102400'],
            'youtube_url'  => ['nullable', 'url', 'regex:/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/i'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        $data = $validator->validated();
        $data['duration'] = $request->duration ?? 0;

        $hasFile = $request->hasFile('url');
        $hasYoutube = !empty($request->youtube_url);

        if ($hasFile) {
            if ($video->url) {
                $old = str_replace('storage/', '', $video->url);
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('url')->store('videos', 'public');
            $data['url'] = 'storage/' . $path;
            $data['youtube_url'] = null;
        }

        if ($hasYoutube) {
            $data['youtube_url'] = $this->convertToEmbedUrl($request->youtube_url);
            $data['url'] = null;
        }

        if (!$hasFile && !$hasYoutube) {
            unset($data['url']);
            unset($data['youtube_url']);
        }

        $video->update($data);

        return response()->json([
            'success' => true,
            'video' => $video
        ]);
    }

    public function destroy(Video $video)
    {
        if ($video->url) {
            $path = str_replace('storage/', '', $video->url);
            Storage::disk('public')->delete($path);
        }

        $video->delete();

        return response()->json(['success' => true]);
    }

    public function videoCompleted(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'الطالب غير موجود'
            ], 403);
        }

        $videoId = $request->video_id;
        $courseId = $request->course_id;

        StudentVideoProgress::updateOrCreate([
            'student_id' => $student->id,
            'video_id'   => $videoId,
            'course_id'  => $courseId,
        ], [
            'completed' => true,
        ]);

        $totalVideos = Video::where('course_id', $courseId)->count();
        $completedVideos = StudentVideoProgress::where('student_id', $student->id)
            ->where('course_id', $courseId)
            ->where('completed', true)
            ->count();

        $courseCompleted = ($totalVideos > 0 && $totalVideos == $completedVideos);
        $isLastVideo = ($completedVideos >= $totalVideos - 1);
        $hasCertificate = Certificate::where('student_id', $student->id)
            ->where('course_id', $courseId)
            ->exists();

        $percentage = $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100) : 0;
        $certificateUrl = null;

        if ($courseCompleted && !$hasCertificate) {
            $certificate = Certificate::create([
                'student_id' => $student->id,
                'course_id' => $courseId,
                'instructor_id' => Course::find($courseId)->instructor_id ?? 1,
                'certificate_number' => 'CERT-' . strtoupper(Str::random(10)),
                'issued_date' => now(),
            ]);
            $certificateUrl = route('certificate.show', $certificate->id);
        } elseif ($hasCertificate) {
            $cert = Certificate::where('student_id', $student->id)->where('course_id', $courseId)->first();
            $certificateUrl = $cert ? route('certificate.show', $cert->id) : null;
        }

        return response()->json([
            'success' => true,
            'course_completed' => $courseCompleted,
            'is_last_video' => $isLastVideo,
            'has_certificate' => $hasCertificate,
            'certificate_url' => $certificateUrl,
            'progress' => [
                'total' => $totalVideos,
                'completed' => $completedVideos,
                'percentage' => $percentage,
                'is_completed' => $courseCompleted
            ]
        ]);
    }

    /**
     * تحويل رابط يوتيوب العادي إلى رابط embed
     */
    private function convertToEmbedUrl($url)
    {
        // استخدام رمز # كمحدد (Delimiter) بدلاً من / لمنع أي تعارض داخلي نهائياً
        $pattern = '#(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})#i';
        
        if (preg_match($pattern, $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        return $url;
    }
}