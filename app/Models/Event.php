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
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'is_published' => 'boolean',
        ];
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
}
