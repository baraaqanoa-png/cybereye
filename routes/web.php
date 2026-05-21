<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User1Controller;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AIChatController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\LessonController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactMessageController;

use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizzController;
use App\Http\Controllers\StudentVideoController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolePermissionController;

use App\Http\Controllers\CertificateController;




// ====================  نظام تسجيل الدخول ====================
Route::prefix('cms')->group(function(){
    Route::get('{guard}/login', [UserAuthController::class, 'showLogin'])->name('view.login');
    Route::post('{guard}/login', [UserAuthController::class, 'login']);
    Route::get('logout', [UserAuthController::class, 'logout'])->name('view.logout');
});

// ==================== الـ Route الافتراضي لتسجيل الدخول ====================
Route::get('/login', function () {

    return redirect()->route('view.login', ['guard' => 'student']);
})->name('login');

// ==================== Routes الرئيسية المحمية ====================
Route::middleware(['auth:admin'])->prefix('cms/admin')->group(function () {
    Route::get('/main', function () {
        return view('cms.admin.main');
    })->name('admin.main');
    Route::get('/main', [AdminController::class, 'main'])->name('admin.main');

});

Route::middleware(['auth:instructor'])->prefix('cms/instructor')->group(function () {
    Route::get('/dashboard', function () {
        return view('cms.instructor.dashboard');
    })->name('instructor.dashboard');
    Route::get('/dashboard', [AdminController::class, 'main'])->name('instructor.dashboard');


});

Route::middleware(['auth:student'])->prefix('cms/student')->group(function () {
    Route::get('/dashboard', function () {
        return view('cms.student.dashboard');
    })->name('student.dashboard');
    Route::get('/dashboard', [AdminController::class, 'main'])->name('student.dashboard');
    Route::get('/ctf', [StudentDashboardController::class, 'ctf'])->name('student.ctf');
    Route::post('/ctf/submit', [StudentDashboardController::class, 'submitFlag'])->name('ctf.submit');
    
    Route::get('/my-courses', [StudentController::class, 'myCourses'])->name('student.my-courses');
    Route::get('/my-certificates', [StudentController::class, 'myCertificates'])->name('student.my-certificates');
});

// ==================== Route رئيسية واحدة ====================
Route::get('/cms/main', function () {
    if (auth('admin')->check()) {
        return redirect()->route('admin.main');
    } elseif (auth('instructor')->check()) {
        return redirect()->route('admin.main');
    } elseif (auth('student')->check()) {
        return redirect()->route('admin.main');
    } else {
        return redirect()->route('view.login', ['guard' => 'student']);
    }
})->name('main');

// ====================  Student Registration (الخارجية) ====================
Route::get('cms/register', [UserAuthController::class, 'showSignup'])->name('view.register');
Route::post('cms/register', [StudentController::class, 'store'])->name('student.register');


// ====================  الرئيسية ====================
Route::prefix('cms/home')->group(function(){
    Route::view('parent', 'cms.home.parent');
    Route::view('contact', 'cms.home.contact')->name('contact');
    Route::post('/contact/send', [ContactMessageController::class, 'store'])->name('contact.store');
    Route::get('/', [DictionaryController::class, 'home'])->name('home');
Route::get('/dictionary', [DictionaryController::class, 'index'])->name('dictionary.index');
Route::resource('dictionary', DictionaryController::class);
Route::post('/dictionary/search', [DictionaryController::class, 'search'])->name('dictionary.search');


});



// ==================== AI Chat ====================
Route::post('/cms/ai/chat', [AIChatController::class, 'chat'])->name('ai.chat');

// ==================== Routes للطلاب ====================
Route::prefix('cms/student')->group(function(){
    Route::view('/', 'cms.parent');
    Route::view('temp', 'cms.temp');

    Route::put('students_update/{id}', [StudentController::class, 'update'])->name('students_update');
    Route::get('students_trashed', [StudentController::class, 'trashed'])->name('students_trashed');
    Route::get('students_restore/{id}', [StudentController::class, 'restore'])->name('students_restore');
    Route::get('students_force/{id}', [StudentController::class, 'force'])->name('students_force');
    Route::get('students_forceAll', [StudentController::class, 'forceAll'])->name('students_forceAll');

    Route::resource('students', StudentController::class);
    Route::resource('users', User1Controller::class);

});




// Student Dashboard Routes
Route::middleware(['auth:student'])->prefix('cms/student')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::post('/enroll', [StudentDashboardController::class, 'enroll'])->name('student.enroll');
    Route::get('/my-courses', [StudentDashboardController::class, 'myCourses'])->name('student.my-courses');
    Route::get('/my-certificates', [StudentDashboardController::class, 'myCertificates'])->name('student.my-certificates');
});




// ==================== Routes admin ====================
Route::prefix('cms/admin')->group(function(){
    
    // الرئيسية
    Route::get('main', [AdminController::class, 'main'])->name('main');
    
    // ادارة المشرفين
    Route::resource('admins', AdminController::class);
    
    // رسائل الاتصال
    Route::get('/admin/contacts/{id}', [ContactMessageController::class, 'show'])->name('admin.contacts.show');
    
    // التصنيفات
    Route::get('categories_trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
    Route::get('categories_restore/{id}', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::get('categories_force/{id}', [CategoryController::class, 'forceDelete'])->name('categories.force');
    Route::resource('categories', CategoryController::class);
    
    // اتمام الفيديو للطالب
    Route::post('/cms/student/video-completed', [StudentVideoController::class, 'markVideoCompleted'])->name('student.video.completed');
    
    // ========== الأدوار والصلاحيات ==========
    // روابط الأدوار
    Route::resource('roles', RoleController::class);
    Route::post('roles-update/{id}', [RoleController::class, 'update'])->name('roles-update');
    
    // روابط الصلاحيات
    Route::resource('permissions', PermissionController::class);
    Route::post('permissions-update/{id}', [PermissionController::class, 'update'])->name('permissions-update');
    
    // روابط إسناد الصلاحيات للأدوار (Role Permissions)
    Route::get('roles/{roleId}/permissions', [RolePermissionController::class, 'index'])->name('roles.permissions.index');
    Route::post('roles/{roleId}/permissions', [RolePermissionController::class, 'store'])->name('roles.permissions.store');
});
// Route::prefix('cms/admin')->group(function(){
//     Route::get('main', [AdminController::class, 'main'])->name('main');
//     Route::resource('admins', AdminController::class);
// // راوت لعرض رسالة واحدة محددة
// Route::get('/admin/contacts/{id}', [ContactMessageController::class, 'show'])->name('admin.contacts.show');
//     // Categories Routes
//     Route::get('categories_trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
//     Route::get('categories_restore/{id}', [CategoryController::class, 'restore'])->name('categories.restore');
//     Route::get('categories_force/{id}', [CategoryController::class, 'forceDelete'])->name('categories.force');
//     Route::resource('categories', CategoryController::class);



//     Route::post('/cms/student/video-completed', [StudentVideoController::class, 'markVideoCompleted'])
//     ->name('student.video.completed');

//     Route::resource('roles' , RoleController::class);
//     Route::post('roles-update/{id}' , [RoleController::class , 'update'])->name('roles-update');

//     Route::resource('permissions' , PermissionController::class);
//     Route::post('permissions-update/{id}' , [PermissionController::class , 'update'])->name('permissions-update');
//     Route::post('/cms/admin/roles/{role}/permissions', [RoleController::class, 'storePermission']);
//    // Route::resource('roles.permissions' , RolePermissionController::class);
//   Route::get('/cms/admin/roles/{id}/permissions', [RoleController::class, 'permissions'])
//     ->name('roles.permissions');
// });

// Route::post('/cms/admin/roles/{roleId}/permissions', [RolePermissionController::class, 'store'])->name('roles.permissions.store');
// Route::get('/cms/admin/roles/{roleId}/permissions', [RolePermissionController::class, 'index'])->name('roles.permissions.index');


// ==================== Routes للمدرسين ====================
Route::prefix('cms/instructor')->group(function(){
    Route::view('/', 'cms.parent');
    Route::view('temp', 'cms.temp');

    Route::put('instructors_update/{id}', [InstructorController::class, 'update'])->name('instructors_update');
    Route::resource('instructors', InstructorController::class);
    Route::resource('users', User1Controller::class);

    Route::get('instructors_trashed', [InstructorController::class, 'trashed'])->name('instructors_trashed');
    Route::get('instructors_restore/{id}', [InstructorController::class, 'restore'])->name('instructors_restore');
    Route::get('instructors_force/{id}', [InstructorController::class, 'force'])->name('instructors_force');
    Route::get('instructors_forceAll', [InstructorController::class, 'forceAll'])->name('instructors_forceAll');

    // Materials Routes
    Route::get('materials/trashed', [MaterialController::class, 'trashed'])->name('materials.trashed');
    Route::get('materials/{id}/restore', [MaterialController::class, 'restore'])->name('materials.restore');
    Route::get('materials/{id}/force', [MaterialController::class, 'forceDelete'])->name('materials.force');
    Route::resource('materials', MaterialController::class);
});

// ==================== Routes للكورسات ====================

// روابط عامة (عرض التفاصيل - للجميع)
Route::prefix('cms/course')->group(function(){
    Route::get('courses/{id}/details', [CourseController::class, 'showCourseDetails'])->name('course.details');
    Route::post('courses/{id}/enroll', [CourseController::class, 'enroll'])->name('course.enroll')->middleware('auth:student');
    Route::post('comments', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::delete('comments/{comment}', [App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/course/{id}/review', [App\Http\Controllers\CourseController::class, 'storeReview'])->name('course.review.store');
});

// روابط إدارة الكورسات (للمدربين فقط)
Route::prefix('cms/course')->middleware('auth:admin,instructor')->group(function(){
    Route::resource('courses', CourseController::class);
    // Route::resource('lessons', App\Http\Controllers\LessonController::class);
    Route::get('admin/courses/{course}/lessons', [CourseController::class, 'manageLessons'])->name('video.index');
});

// إذا كنتِ تحتاجين لرابط view منفصل، اتركيه هكذا:
Route::view('details', 'cms/courseDetails/details');
Route::get('course/{id}/player', [CourseController::class, 'showCoursePlayer'])->name('course.player');



// ==================== Routes للفيديوهات ====================
Route::prefix('cms/video')->group(function(){

    Route::put('videos_update/{id}', [VideoController::class, 'update'])->name('videos_update');
      Route::get('player/{courseId}', [VideoController::class, 'player'])->name('videos.player');
Route::get('player/{courseId}', [VideoController::class, 'player'])->name('videos.player');
    Route::resource('videos', VideoController::class);
});

Route::view('details', 'cms/courseDetails/details');
Route::view('index', 'cms/course/video/index');



 /////////////////Question Route //////////////////////
  Route::prefix('cms/question')->group(function(){
    Route::resource('questions', QuestionController::class);

  Route::get('questions_trashed', [QuestionController::class, 'trashed'])->name('questions_trashed');

    Route::post('questions_restore/{id}', [QuestionController::class, 'restore'])->name('questions_restore');

    Route::delete('questions_force/{id}', [QuestionController::class, 'forceDelete'])->name('questions_force');

// Route::put('instructors_update/{id}', [InstructorController::class, 'update'])->name('courses_update');
Route::resource('courses', CourseController::class);
// Route::resource('users', User1sController::class);
 Route::view('details','cms/courseDetails/details');


});

//////////////// Quizz Route //////////////////////////////
  Route::prefix('cms/quizz')->group(function(){

Route::get('/quizzs/{id}/start', [QuizzController::class, 'start'])->name('quiz.start');

//Route::get('/quizzs/{quiz}/start', [QuizzController::class, 'show'])->name('quiz.show');
Route::post('/quiz/{quiz}/submit', [QuizzController::class, 'submit'])->name('quiz.submit');
Route::get('/quiz/{quiz}/result', [QuizzController::class, 'result'])->name('quiz.result');
Route::post('/quiz/{quiz}/save-temp', [QuizzController::class, 'saveTemp'])->name('quiz.saveTemp');

Route::post('/quizzs_restore/{id}', [QuizzController::class, 'restore'])->name('quizzs.restore');
Route::delete('/quizzs_force/{id}', [QuizzController::class, 'forceDelete'])->name('quizzs.force');

    Route::resource('quizzs', QuizzController::class);
    Route::get('/quizzs/start', [QuizzController::class, 'create'])->name('quizzs.show');

});

Route::prefix('cms/certificate')->group(function(){
    Route::get('/my-certificates', [CertificateController::class, 'myCertificates'])->name('certificate.my-certificates');
    Route::get('/{id}', [CertificateController::class, 'show'])->name('certificate.show');
    Route::get('/{id}/download', [CertificateController::class, 'download'])->name('certificate.download');
});

// Route::prefix('cms/certificate')->group(function(){
//     Route::get('/{id}', [CertificateController::class, 'show'])->name('certificate.show');
//     Route::get('/{id}/download', [CertificateController::class, 'download'])->name('certificate.download');
//     Route::get('/my-certificates', [CertificateController::class, 'myCertificates'])->name('certificate.my-certificates');
// });

    Route::get('/quiz/{quiz}/review', [QuizzController::class, 'review'])->name('quiz.review');
    Route::get('/quiz/studentResult', [QuizzController::class, 'studentResults'])->name('quiz.studentResults');

   Route::get('/quizzs_trashed', [QuizzController::class, 'trashed'])->name('quizzs.trashed');

// 1. راوت تصفير الرقم (البادج) عند فتح الجرس - POST
Route::post('/mark-notification-read', function () {
    if(auth('admin')->check()){
        auth('admin')->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
})->name('markNotificationRead');

// 2. راوت فتح الإشعار وتحويلك لصفحة الطالب - GET
Route::get('/notification/{id}/view', function ($id) {
    $notification = auth('admin')->user()->notifications()->findOrFail($id);

    $notification->markAsRead(); // تحديد كـ مقروء

    // التعديل هون: بنشيك إذا الـ url موجود، إذا مش موجود بنرجعه عالرئيسية أو صفحة الكنتاكت
    $url = isset($notification->data['url']) ? $notification->data['url'] : route('contact');

    return redirect($url);
})->name('openNotification');
