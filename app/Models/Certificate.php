<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_registration_id',
        'certificate_number',
        'participant_name',
        'event_title',
        'event_date',
        'completion_date',
        'certificate_template',
        'certificate_path',
        'is_generated',
        'certificate_hash',
        'blockchain_tx_hash',
        'blockchain_address',
        'is_blockchain_verified',
        'blockchain_issued_at',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'completion_date' => 'date',
            'is_generated' => 'boolean',
            'is_blockchain_verified' => 'boolean',
            'blockchain_issued_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($certificate) {
            if (!$certificate->certificate_number) {
                $certificate->certificate_number = 'CERT-' . strtoupper(Str::random(8)) . '-' . date('Y');
            }
        });
    }

    public function eventRegistration()
    {
        return $this->belongsTo(EventRegistration::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, EventRegistration::class, 'id', 'id', 'event_registration_id', 'user_id');
    }

    public function event()
    {
        return $this->hasOneThrough(Event::class, EventRegistration::class, 'id', 'id', 'event_registration_id', 'event_id');
    }

    public function getCertificateUrlAttribute()
    {
        if (!$this->certificate_path) {
            return null;
        }

        return asset('storage/' . $this->certificate_path);
    }

    public function generateCertificateHash(): string
    {
        $data = [
            'certificate_number' => $this->certificate_number,
            'participant_name' => $this->participant_name,
            'event_title' => $this->event_title,
            'event_date' => $this->event_date->format('Y-m-d'),
            'completion_date' => $this->completion_date->format('Y-m-d'),
        ];

        ksort($data);
        return hash('sha256', json_encode($data));
    }

    public function getVerificationUrlAttribute()
    {
        if (!$this->certificate_hash) {
            return null;
        }

        return route('certificates.verify', ['hash' => $this->certificate_hash]);
    }
}
