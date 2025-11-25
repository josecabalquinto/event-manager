<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_number',
        'course',
        'year_level',
        'section',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getAvailableCourses()
    {
        return ['BSIT', 'BSIS', 'BSEMC', 'BLIS'];
    }

    public static function getAvailableYearLevels()
    {
        return ['1st Year', '2nd Year', '3rd Year', '4th Year'];
    }

    public function getFullInfoAttribute()
    {
        return "{$this->student_number} - {$this->course} {$this->year_level} {$this->section}";
    }
}
