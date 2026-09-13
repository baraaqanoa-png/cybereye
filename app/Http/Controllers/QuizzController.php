<?php

namespace App\Http\Controllers;


use App\Models\Quizz;
use App\Models\Course;
use App\Models\QuizResult;
use App\Models\StudentAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizzController extends Controller
{
    /**
     * عرض قائمة الكويزات.
     */
    public function index()
    {
        // ========== للطالب ==========
        if(auth('student')->check()) {
            $student = auth('student')->user()->actor;
            
            // جلب الكويزات الخاصة بالكورسات التي سجل فيها الطالب فقط
            $quizzes = Quizz::whereHas('course.students', function($query) use ($student) {
                $query->where('students.id', $student->id);
            })->get();
            
            return view('cms.quizz.start', compact('quizzes'));
        }
        
        // ========== للمدرب أو الأدمن ==========
        if(auth('instructor')->check() || auth('admin')->check()) {
            $quizzs = Quizz::with('course')->latest()->paginate(10);
            return view('cms.quizz.index', compact('quizzs'));
        }
        
        // إذا لم يكن مسجلاً دخوله
        return redirect()->route('login');
    }

    /**
     * عرض نموذج إنشاء كويز جديد.
     */
    public function create()
    {
        $courses = Course::all();
        return view('cms.quizz.create', compact('courses'));
    }

    /**
     * عرض صفحة كويز معين (للمعاينة).
     */
    public function show($id)
    {
        $quizz = Quizz::with('questions')->findOrFail($id);
        $questions = $quizz->questions;
        return view('cms.quizz.show', compact('quizz', 'questions'));
    }

    /**
     * حفظ كويز جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'total_marks' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'icon' => 'error',
                'title' => $validator->errors()->first(),
            ], 400);
        }

        try {
            Quizz::create($request->all());
            return response()->json([
                'icon' => 'success',
                'title' => 'تم إنشاء الكويز بنجاح',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'icon' => 'error',
                'title' => 'حدث خطأ: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * عرض نموذج تعديل كويز.
     */
    public function edit(Quizz $quizz)
    {
        $courses = Course::all();
        return view('cms.quizz.edit', compact('quizz', 'courses'));
    }

    /**
     * تحديث بيانات كويز في قاعدة البيانات.
     */
    public function update(Request $request, Quizz $quizz)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'total_marks' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'icon' => 'error',
                'title' => $validator->errors()->first(),
            ], 400);
        }

        try {
            $quizz->update($request->all());
            return response()->json([
                'icon' => 'success',
                'title' => 'تم تحديث الكويز بنجاح',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'icon' => 'error',
                'title' => 'حدث خطأ: ' . $e->getMessage(),
            ], 500);
        }
    }



 public function destroy(Quizz $quizz)
{
    // أرشفة جميع الأسئلة المرتبطة
    $quizz->questions()->delete();

    // أرشفة الكويز
    $quizz->delete();

    return response()->json([
        'success' => true,
        'message' => 'تم نقل الكويز وأسئلته إلى الأرشيف'
    ]);
}


public function restore($id)
{
     $quizz = Quizz::onlyTrashed()->findOrFail($id);
    $quizz->restore();

    return response()->json([
        'success' => true,
        'message' => 'تم استعادة السؤال بنجاح'
    ]);
}


public function forceDelete($id)
{
    $quizz = Quizz::onlyTrashed()->findOrFail($id);

    $quizz->questions()->forceDelete();

    $quizz->forceDelete();

    return response()->json([
        'success' => true,
        'message' => 'تم حذف الكويز وأسئلته نهائياً']);
}

  
    public function trashed()
    {
        $quizzs = Quizz::onlyTrashed()->with('course')->get();
        return view('cms.quizz.trashed', compact('quizzs'));
    }

    public function force($id)
    {
        $quizz = Quizz::onlyTrashed()->findOrFail($id);
        $quizz->forceDelete();
        return back()->with('success', 'تم حذف الكويز نهائياً');
    }



    public function start($quizzId)
    {
        $quizz = Quizz::with('questions')->findOrFail($quizzId);
        $questions = $quizz->questions->shuffle();
        $tempAnswers = session()->get("quiz_{$quizzId}_temp_answers", []);
        return view('cms.quizz.start', compact('quizz', 'questions', 'tempAnswers'));


    // التحقق من وجود الكويز
    $quizz = Quizz::find($quizzId);
    if (!$quizz) {
        return redirect()->route('quizzs.index')->with('error', 'هذا الكويز غير موجود');
    }

    // التحقق من وجود أسئلة مرتبطة
    $questions = $quizz->questions;
    if ($questions->count() == 0) {
        return redirect()->route('quizzs.index')->with('error', 'هذا الكويز غير متاح حالياً (لا توجد أسئلة)');
    }
    }

    /**
     * حفظ إجابات الطالب والنتيجة – بدون تسجيل دخول (يستخدم student_id = 1).
     */

     public function submit(Request $request, $quizzId)
    {
        $student = auth('student')->user()->actor;
        $studentId = $student->id;
        
        $quiz = Quizz::with('questions')->findOrFail($quizzId);
        $answers = $request->input('answers', []);
        
        DB::beginTransaction();
        try {
            $totalPoints = 0;
            $earnedPoints = 0;
            
            foreach ($quiz->questions as $question) {
                $userAnswer = $answers[$question->id] ?? null;
                $isCorrect = ($userAnswer === $question->correct_answer);
                $pointsEarned = $isCorrect ? $question->points : 0;
                $totalPoints += $question->points;
                $earnedPoints += $pointsEarned;
                
                StudentAnswer::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'quizz_id' => $quiz->id,
                        'question_id' => $question->id,
                    ],
                    [
                        'answer' => $userAnswer,
                        'is_correct' => $isCorrect,
                        'points_earned' => $pointsEarned,
                        'submitted_at' => now(),
                    ]
                );
            }
            
            QuizResult::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'quizz_id' => $quiz->id,
                ],
                [
                    'score' => $earnedPoints,
                    'total_points' => $totalPoints,
                    'submitted_at' => now(),
                ]
            );
            
            DB::commit();
            
            return redirect()->route('quiz.result', $quiz->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء حفظ الإجابات: ' . $e->getMessage());
        }
    }

//      public function submit(Request $request, $quizzId)
// {
//     // ✅ جلب الطالب المسجل حالياً
//     $user = auth('student')->user();
//         $studentId = $user->id;
    
//     $quiz = Quizz::with('questions')->findOrFail($quizzId);
//     $answers = $request->input('answers', []);
    
//     DB::beginTransaction();
//     try {
//         $totalPoints = 0;
//         $earnedPoints = 0;
        
//         foreach ($quiz->questions as $question) {
//             $userAnswer = $answers[$question->id] ?? null;
//             $isCorrect = ($userAnswer === $question->correct_answer);
//             $pointsEarned = $isCorrect ? $question->points : 0;
//             $totalPoints += $question->points;
//             $earnedPoints += $pointsEarned;
            
//             StudentAnswer::updateOrCreate(
//                 [
//                     'student_id' => $studentId,
//                     'quizz_id' => $quiz->id,
//                     'question_id' => $question->id,
//                 ],
//                 [
//                     'answer' => $userAnswer,
//                     'is_correct' => $isCorrect,
//                     'points_earned' => $pointsEarned,
//                     'submitted_at' => now(),
//                 ]
//             );
//         }
        
//         QuizResult::updateOrCreate(
//             [
//                 'student_id' => $studentId,
//                 'quizz_id' => $quiz->id,
//             ],
//             [
//                 'score' => $earnedPoints,
//                 'total_points' => $totalPoints,
//                 'submitted_at' => now(),
//             ]
//         );
        
//         DB::commit();
        
//         return redirect()->route('quiz.result', $quiz->id);
//     } catch (\Exception $e) {
//         DB::rollBack();
//         return back()->with('error', 'حدث خطأ أثناء حفظ الإجابات: ' . $e->getMessage());
//     }
// }
    // public function submit(Request $request, $quizzId)
    // {
    //     // استخدام معرف ثابت للتجربة
    //     $studentId = 1; // يمكن تغييره حسب الحاجة

    //     $quiz = Quizz::with('questions')->findOrFail($quizzId);
    //     $answers = $request->input('answers', []);

    //     DB::beginTransaction();
    //     try {
    //         $totalPoints = 0;
    //         $earnedPoints = 0;

    //         foreach ($quiz->questions as $question) {
    //             $userAnswer = $answers[$question->id] ?? null;
    //             $isCorrect = ($userAnswer === $question->correct_answer);
    //             $pointsEarned = $isCorrect ? $question->points : 0;
    //             $totalPoints += $question->points;
    //             $earnedPoints += $pointsEarned;

    //             StudentAnswer::updateOrCreate(
    //                 [
    //                     'student_id' => $studentId,
    //                     'quizz_id' => $quiz->id,
    //                     'question_id' => $question->id,
    //                 ],
    //                 [
    //                     'answer' => $userAnswer,
    //                     'is_correct' => $isCorrect,
    //                     'points_earned' => $pointsEarned,
    //                     'submitted_at' => now(),
    //                 ]
    //             );
    //         }

    //         QuizResult::updateOrCreate(
    //             [
    //                 'student_id' => $studentId,
    //                 'quizz_id' => $quiz->id,
    //             ],
    //             [
    //                 'score' => $earnedPoints,
    //                 'total_points' => $totalPoints,
    //                 'submitted_at' => now(),
    //             ]
    //         );

    //         DB::commit();

    //         session()->flash('quiz_result', [
    //             'score' => $earnedPoints,
    //             'total' => $totalPoints,
    //             'quiz_id' => $quiz->id,
    //         ]);

    //         return redirect()->route('quiz.result', $quiz->id);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'حدث خطأ أثناء حفظ الإجابات: ' . $e->getMessage());
    //     }
    // }


    public function result($quizId)
    {
        $quiz = Quizz::with('course.instructor.user1')->find($quizId);
        
        if (!$quiz) {
            return redirect()->route('quizzs.index')->with('error', 'الكويز المطلوب غير موجود');
        }
        
        $course = $quiz->course;
        $student = auth('student')->user()->actor;
        $studentId = $student->id;
        
        $result = QuizResult::where('student_id', $studentId)
            ->where('quizz_id', $quizId)
            ->first();
        
        if (!$result) {
            return redirect()->route('quiz.start', $quizId)->with('error', 'لا توجد نتيجة لهذا الامتحان. يرجى تقديم الامتحان أولاً.');
        }
        
        return view('cms.quizz.result', compact('quiz', 'result', 'course', 'student'));
    }

    // public function result($quizId)
    // {
    //     // التحقق من وجود الكويز
    //     $quiz = Quizz::with('course.instructor.user1')->find($quizId);
        
    //     if (!$quiz) {
    //         return redirect()->route('quizzs.index')->with('error', 'الكويز المطلوب غير موجود');
    //     }
    
    //     // جلب الكورس المرتبط بالكويز
    //     $course = $quiz->course;
    
    //     // التحقق من وجود أسئلة للكويز
    //     if ($quiz->questions->count() == 0) {
    //         return redirect()->route('quizzs.index')->with('error', 'هذا الكويز لا يحتوي على أسئلة لعرض النتيجة');
    //     }
    
    //     // استخدام الطالب المسجل حالياً (بدلاً من ID ثابت)
    //     $student = auth('student')->user()->actor;
    //     $studentId = $student->id;
    
    //     // جلب النتيجة
    //     $result = QuizResult::where('student_id', $studentId)
    //         ->where('quizz_id', $quizId)
    //         ->first();
    
    //     if (!$result) {
    //         return redirect()->route('quiz.start', $quizId)->with('error', 'لا توجد نتيجة لهذا الامتحان. يرجى تقديم الامتحان أولاً.');
    //     }
    
    //     // عرض صفحة النتيجة مع المتغيرات
    //     return view('cms.quizz.result', compact('quiz', 'result', 'course', 'student'));
    // }
    public function review($quizId)
{
    $student = auth('student')->user()->actor;
    $studentId = $student->id;
    
    $quiz = Quizz::with('questions')->findOrFail($quizId);
    $answers = StudentAnswer::where('student_id', $studentId)
        ->where('quizz_id', $quizId)
        ->get()
        ->keyBy('question_id');
    
    $questions = $quiz->questions;
    
    return view('cms.quizz.review', compact('quiz', 'questions', 'answers'));
}
    // public function review($quizId)
    // {
    //     $studentId = 1;
    //     $quiz = Quizz::with('questions')->findOrFail($quizId);
    //     $answers = StudentAnswer::where('student_id', $studentId)
    //         ->where('quizz_id', $quizId)
    //         ->get()
    //         ->keyBy('question_id');

    //     $questions = $quiz->questions;
    //     $tempAnswers = [];

    //     return view('cms.quizz.review', compact('quiz', 'questions', 'answers', 'tempAnswers'));
    // }

    /**
     * حفظ الإجابات مؤقتاً أثناء التنقل بين الأسئلة.
     */
    public function saveTemp(Request $request, $quizzId)
    {
        $answers = $request->input('answers', []);
        session()->put("quiz_{$quizzId}_temp_answers", $answers);
        return response()->json(['status' => 'saved']);
    }


public function studentResults()
{
    // استعلام يجمع البيانات من QuizResult مع ربط أسماء الطلاب والكويزات
    $results = QuizResult::with(['student', 'quizz'])
        ->orderBy('submitted_at', 'desc')
        ->paginate(15);

    // تحويل البيانات إلى شكل مناسب للـ view
    $formattedResults = $results->map(function ($item) {
        return (object)[
            'student_id' => $item->student_id,
            'student_name' => $item->student->name ?? $item->student->username ?? 'غير محدد',
            'quiz_id' => $item->quizz_id,
            'quiz_title' => $item->quizz->title ?? 'غير محدد',
            'score' => $item->score,
            'total_points' => $item->total_points,
            'submitted_at' => $item->submitted_at,
        ];
    });

    // إحصائيات
      $avgScore = QuizResult::selectRaw('AVG((score / total_points) * 100) as avg')->first()->avg ?? 0;
    $highestScore = QuizResult::selectRaw('MAX((score / total_points) * 100) as max')->first()->max ?? 0;
$totalStudents = QuizResult::distinct('student_id')->count('student_id');
$averageScore = round($avgScore, 2);
$highestScore = round($highestScore, 2) . '%';

    $paginatedResults = $results;
    $paginatedResults->getCollection()->transform(function ($item) use ($formattedResults) {
        return $formattedResults->firstWhere('student_id', $item->student_id);
    });

    return view('cms.quizz.studentResult', [
        'results' => $paginatedResults,
        'totalStudents' => $totalStudents,
        'averageScore' => $averageScore,
        'highestScore' => $highestScore,
    ]);
}


}
