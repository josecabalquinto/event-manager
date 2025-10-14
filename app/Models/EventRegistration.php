<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'qr_code',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'guest_name',
        'guest_email',
        'guest_student_id',
        'guest_course',
        'guest_year_level',
        'guest_section',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            if (!$registration->qr_code) {
                $registration->qr_code = Str::uuid()->toString();
            }
        });
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checkIn()
    {
        return $this->hasOne(CheckIn::class);
    }

    public function isCheckedIn()
    {
        return $this->checkIn()->exists();
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    public function hasCertificate()
    {
        return $this->certificate()->exists();
    }

    public function isGuestRegistration()
    {
        return is_null($this->user_id);
    }

    public function getRegistrantNameAttribute()
    {
        return $this->isGuestRegistration() ? $this->guest_name : $this->user->name;
    }

    public function getRegistrantEmailAttribute()
    {
        return $this->isGuestRegistration() ? $this->guest_email : $this->user->email;
    }

    public function getRegistrantStudentIdAttribute()
    {
        return $this->isGuestRegistration() ? $this->guest_student_id : $this->user->student_id;
    }

    public function getRegistrantCourseAttribute()
    {
        return $this->isGuestRegistration() ? $this->guest_course : $this->user->course;
    }

    public function getRegistrantYearLevelAttribute()
    {
        return $this->isGuestRegistration() ? $this->guest_year_level : $this->user->year_level;
    }

    public function getRegistrantSectionAttribute()
    {
        return $this->isGuestRegistration() ? $this->guest_section : $this->user->section;
    }

    public function getRegistrantFullInfoAttribute()
    {
        $studentId = $this->registrant_student_id;
        $course = $this->registrant_course;
        $yearLevel = $this->registrant_year_level;
        $section = $this->registrant_section;

        if (!$studentId) {
            return null;
        }

        return $studentId . ' - ' . $course . ' ' . $yearLevel . ' ' . $section;
    }

    // Approval system methods
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function approve($adminUser)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $adminUser->id,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    public function reject($adminUser, $reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $adminUser->id,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    // Scopes for filtering
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
