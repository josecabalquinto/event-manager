<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'latitude',
        'longitude',
        'banner_image',
        'max_participants',
        'event_type_id',
        'event_organizer_id',
        'is_published',
        'has_certificate',
        'certificate_template',
        'certificate_title',
        'certificate_description',
        'certificate_background_color',
        'certificate_text_color',
        'certificate_border_style',
        'certificate_signatories',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'is_published' => 'boolean',
            'has_certificate' => 'boolean',
            'certificate_signatories' => 'json',
        ];
    }

    public function eventType()
    {
        return $this->belongsTo(EventType::class);
    }

    public function eventOrganizer()
    {
        return $this->belongsTo(EventOrganizer::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class);
    }

    public function getAvailableSpotsAttribute()
    {
        if (!$this->max_participants) {
            return null;
        }

        return $this->max_participants - $this->registrations()->count();
    }

    public function isFull()
    {
        if (!$this->max_participants) {
            return false;
        }

        return $this->registrations()->count() >= $this->max_participants;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }

    public function getBannerImageUrlAttribute()
    {
        if (!$this->banner_image) {
            return null;
        }

        return asset('storage/' . $this->banner_image);
    }

    public function hasBannerImage()
    {
        return !empty($this->banner_image);
    }

    public function certificates()
    {
        return $this->hasManyThrough(Certificate::class, EventRegistration::class);
    }

    public function getCheckedInParticipantsCountAttribute()
    {
        return $this->checkIns()->count();
    }

    public function getCertificatesGeneratedCountAttribute()
    {
        return $this->certificates()->where('is_generated', true)->count();
    }

    public function getDefaultCertificateTemplate()
    {
        if ($this->certificate_template) {
            return $this->certificate_template;
        }

        return "This is to certify that {participant_name} has successfully completed {event_title} on {event_date}.";
    }
}
