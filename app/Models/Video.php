<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url',
        'duration',
        'lesson_id',
        'course_id',
        'youtube_url',
        'order_number'

      
    ];

    // العلاقة مع الدرس
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
    public function course()
{
    return $this->belongsTo(Course::class);
}

}
