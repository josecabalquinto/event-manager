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
}
