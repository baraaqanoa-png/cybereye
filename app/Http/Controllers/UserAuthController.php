<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\Student;
use App\Models\User1;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{



    public function showLogin($guard){
           return  response()->view('cms.auth.login',compact('guard'));
    }

//     public function login(Request $request)
// {
//     $request->validate([
//         'email' => 'required|email|exists:user1s,email',
//         'password' => 'required',
//         'guard' => 'required|in:admin,student,instructor',
//     ]);

//     $guard = $request->guard;
    
//     // تسجيل الخروج من جميع الـ guards
//     auth('admin')->logout();
//     auth('student')->logout();
//     auth('instructor')->logout();
//     $request->session()->invalidate();
//     $request->session()->regenerateToken();

//     $credentials = [
//         'email' => $request->email,
//         'password' => $request->password,
//     ];

//     // ✅ جلب المستخدم
//     $user = User1::where('email', $request->email)->first();
    
//     // ✅ إذا كان guard_name فارغ، قم بتعيينه بناءً على الـ guard المختار
//     if($user && empty($user->guard_name)) {
//         $user->guard_name = $guard;
//         $user->save();
//     }
    
//     // ✅ التحقق من تطابق الـ guard مع نوع الحساب
//     if($user && $user->guard_name !== $guard) {
//         // أعط رسالة أوضح
//         $expectedGuard = $user->guard_name;
//         $guardName = '';
//         if($expectedGuard == 'student') $guardName = 'طالب';
//         elseif($expectedGuard == 'instructor') $guardName = 'مدرب';
//         elseif($expectedGuard == 'admin') $guardName = 'مدير';
        
//         return response()->json([
//             'icon' => 'error',
//             'title' => "نوع الحساب غير صحيح! هذا الحساب مخصص لـ $guardName"
//         ], 400);
//     }

//     if (Auth::guard($guard)->attempt($credentials)) {
//         $user = Auth::guard($guard)->user();
        
//         // تأكد من حفظ guard_name
//         if($user->guard_name != $guard) {
//             $user->guard_name = $guard;
//             $user->save();
//         }

//         return response()->json([
//             'icon' => 'success',
//             'title' => 'تم تسجيل الدخول بنجاح',
//             'redirect' => '/cms/admin/main'
//         ]);
//     }

//     return response()->json([
//         'icon' => 'error',
//         'title' => 'بيانات الدخول غير صحيحة'
//     ], 401);
// }


    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email|exists:user1s,email',
    //         'password' => 'required',
    //         'guard' => 'required|in:admin,student,instructor',
    //     ]);
    
    //     $guard = $request->guard;
        
    //     // تسجيل الخروج من جميع الـ guards
    //     auth('admin')->logout();
    //     auth('student')->logout();
    //     auth('instructor')->logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();
    
    //     $credentials = [
    //         'email' => $request->email,
    //         'password' => $request->password,
    //     ];
    
    //     // ✅ التحقق من أن المستخدم لديه نفس الـ guard الذي اختاره
    //     $user = User1::where('email', $request->email)->first();
        
    //     if($user && $user->guard_name !== $guard) {
    //         return response()->json([
    //             'icon' => 'error',
    //             'title' => 'نوع الحساب غير صحيح ' 
    //         ], 400);
    //     }
    
    //     if (Auth::guard($guard)->attempt($credentials)) {
    //         $user = Auth::guard($guard)->user();
    //         $user->guard_name = $guard;
    //         $user->save();
    
    //         return response()->json([
    //             'icon' => 'success',
    //             'title' => 'تم تسجيل الدخول بنجاح',
    //             'redirect' => '/cms/admin/main'
    //         ]);
    //     }
    
    //     return response()->json([
    //         'icon' => 'error',
    //         'title' => 'بيانات الدخول غير صحيحة'
    //     ], 401);
    // }




    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:user1s,email',
            'password' => 'required',
            'guard' => 'required|in:admin,student,instructor',
        ]);
    
        $guard = $request->guard;
        auth('admin')->logout();
    auth('student')->logout();
    auth('instructor')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
    
        if (Auth::guard($guard)->attempt($credentials)) {
            $user = Auth::guard($guard)->user();
            $user->guard_name = $guard;
            $user->save();
    
            // التوجيه حسب الـ guard
            $redirect = match($guard) {
                'admin' => '/cms/admin/main',
                'instructor' => '/cms/admin/main',
                'student' => '/cms/admin/main',
                default => '/',
            };
            
        return response()->json([
            'icon' => 'success',
            'title' => 'login success',
            'redirect' => $redirect
        ]);
        // if (Auth::guard($guard)->attempt($credentials)) {
    
        //     $request->session()->regenerate();
    
        //     return response()->json([
        //         'icon' => 'success',
        //         'title' => 'login success',
        //         'redirect' => '/cms/'.$guard.'/main'
        //     ]);
        // }
    
        return response()->json([
            'icon' => 'error',
            'title' => 'بيانات الدخول غير صحيحة'
        ], 401);
    }}


    // public function logout(Request  $request){
    //     $guard = auth('admin')->check() ? 'admin':'student';
    //     Auth::guard($guard)->logout();
    //     $request->session()->invalidate();
    //     return redirect()->route('view.login', $guard);
    // }


    public function logout(Request $request)
{
    // تسجيل الخروج من جميع الـ guards
    auth('admin')->logout();
    auth('student')->logout();
    auth('instructor')->logout();
    
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
  
    return redirect()->route('view.login', ['guard' => 'user1']);
}

    public function changePassword(){

    }

    public function resetPassword(Request $request){

    }

    public function editProfile(){

    }

    public function updateProfile(Request $request, $id ){

    }

    public function showSignup()
{

    return view('cms.auth.login');
}



public function register(Request $request)
{
    $validator = Validator($request->all(), [
        'username' => 'required|string|min:3|max:50',
        'email' => 'required|email|unique:user1s,email',
        'password' => 'required|min:8|confirmed',
        'firstName' => 'required|string|min:2|max:20',
        'lastName' => 'required|string|min:2|max:20',
    ], [
        'username.required' => 'اسم المستخدم مطلوب',
        'email.required' => 'البريد الإلكتروني مطلوب',
        'email.email' => 'البريد الإلكتروني غير صحيح',
        'email.unique' => 'البريد الإلكتروني موجود بالفعل',
        'password.required' => 'كلمة المرور مطلوبة',
        'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
        'password.confirmed' => 'كلمة المرور غير متطابقة',
        'firstName.required' => 'الاسم الأول مطلوب',
        'lastName.required' => 'الاسم الأخير مطلوب',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'icon' => 'error',
            'title' => $validator->errors()->first(),
        ], 400);
    }

    try {
        DB::beginTransaction();
        
       
        $user = User1::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);
        
        
        $student = Student::create([
            'user_id' => $user->id,
            'progress' => '0',
            'level' => 'beginner',
            'specialization' => 'General',
            'role' => 'student',
            'status' => 'active',
            'enrollment_date' => now(),
        ]);
        

        $user->update([
            'actor_id' => $student->id,
            'actor_type' => 'App\Models\Student',
        ]);

        $studentRole = \Spatie\Permission\Models\Role::where('name', 'student')->first();
        if($studentRole) {
            $user->assignRole($studentRole);
        }
        
        DB::commit();
        
    
        Auth::login($user);
        
        return response()->json([
            'icon' => 'success',
            'title' => 'تم إنشاء الحساب بنجاح',
        ], 200);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'icon' => 'error',
            'title' => 'حدث خطأ: ' . $e->getMessage(),
        ], 500);
    }
}

}
