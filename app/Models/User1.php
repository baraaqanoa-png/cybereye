<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;



class User1 extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\User1Factory> */
    use HasFactory, Notifiable;
    use SoftDeletes,HasRoles;

    protected $table = 'user1s';





    protected $fillable = [
        'username',
        'password',
        'email',
        'role',
        'actor_type', 'actor_id'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // ========== العلاقات ==========

    public function actor()
    {
        return $this->morphTo();
    }

// public function isStudent()
// {
//     return $this->actor_type === Student::class && $this->actor;
// }

// public function isInstructor()
// {
//     return $this->actor_type === Instructor::class && $this->actor;
// }

// public function isAdmin()
// {
//     return $this->actor_type === Admin::class && $this->actor;
// }


// public function student() {
//     return $this->hasOne(Student::class);
// }

public function student() {
    // هنا نحدد صراحة أن العمود الرابط في جدول students هو 'user_id'
    return $this->hasOne(Student::class, 'user_id', 'id');
}
// public function getGuardNameAttribute()
// {
//     if ($this->role === 'Admin') {
//         return 'admin';
//     } elseif ($this->role === 'instructor') {
//         return 'instructor';
//     } elseif ($this->role === 'student') {
//         return 'student';
//     }
//     return 'web';
// }

}
