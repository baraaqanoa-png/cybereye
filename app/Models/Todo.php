<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'title',
        'due_date', 
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'due_date'     => 'datetime',
    ];
}