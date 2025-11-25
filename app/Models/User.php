<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'student_id',
        'course',
        'year_level',
        'section',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function getFullStudentInfoAttribute()
    {
        if (!$this->student_id) {
            return null;
        }

        return $this->student_id . ' - ' . $this->course . ' ' . $this->year_level . ' ' . $this->section;
    }

    public static function getAvailableCourses()
    {
        return ['BSIT', 'BSIS', 'BLIS', 'BSEMC'];
    }

    public static function getAvailableYearLevels()
    {
        return ['1st Year', '2nd Year', '3rd Year', '4th Year'];
    }
}
