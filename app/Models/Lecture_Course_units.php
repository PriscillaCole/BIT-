<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture_Course_units extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_unit_code',
        'email',
        
    ];
}
