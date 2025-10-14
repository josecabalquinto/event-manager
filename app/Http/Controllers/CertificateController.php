<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::whereHas('eventRegistration', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['eventRegistration.event', 'eventRegistration.user'])
            ->orderBy('completion_date', 'desc')
            ->get()
            ->map(function ($certificate) {
                return [
                    'id' => $certificate->id,
                    'certificate_number' => $certificate->certificate_number,
                    'participant_name' => $certificate->participant_name,
                    'event_title' => $certificate->event_title,
                    'event_date' => $certificate->event_date->format('Y-m-d'),
                    'completion_date' => $certificate->completion_date->format('Y-m-d'),
                    'is_generated' => $certificate->is_generated,
                    'certificate_url' => $certificate->certificate_url,
                    'certificate_hash' => $certificate->certificate_hash,
                    'blockchain_tx_hash' => $certificate->blockchain_tx_hash,
                    'is_blockchain_verified' => $certificate->is_blockchain_verified,
                    'blockchain_issued_at' => $certificate->blockchain_issued_at,
                    'verification_url' => $certificate->verification_url,
                    'event' => [
                        'id' => $certificate->eventRegistration->event->id,
                        'title' => $certificate->eventRegistration->event->title,
                    ],
                ];
            });

        return Inertia::render('Certificates/Index', [
            'certificates' => $certificates,
        ]);
    }

    public function show(Certificate $certificate)
    {
        // Check if the certificate belongs to the authenticated user
        if ($certificate->eventRegistration->user_id !== Auth::id()) {
            abort(403);
        }

        $certificate->load(['eventRegistration.event', 'eventRegistration.user']);

        return Inertia::render('Certificates/Show', [
            'certificate' => [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'participant_name' => $certificate->participant_name,
                'event_title' => $certificate->event_title,
                'event_date' => $certificate->event_date->format('Y-m-d'),
                'completion_date' => $certificate->completion_date->format('Y-m-d'),
                'is_generated' => $certificate->is_generated,
                'certificate_url' => $certificate->certificate_url,
                'certificate_hash' => $certificate->certificate_hash,
                'blockchain_tx_hash' => $certificate->blockchain_tx_hash,
                'is_blockchain_verified' => $certificate->is_blockchain_verified,
                'blockchain_issued_at' => $certificate->blockchain_issued_at,
                'verification_url' => $certificate->verification_url,
                'event' => [
                    'id' => $certificate->eventRegistration->event->id,
                    'title' => $certificate->eventRegistration->event->title,
                    'location' => $certificate->eventRegistration->event->location,
                ],
            ],
        ]);
    }

    public function download(Certificate $certificate)
    {
        // Check if the certificate belongs to the authenticated user
        if ($certificate->eventRegistration->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$certificate->is_generated || !$certificate->certificate_path) {
            return back()->with('error', 'Certificate is not yet available for download.');
        }

        $filePath = storage_path('app/public/' . $certificate->certificate_path);

        if (!file_exists($filePath)) {
            return back()->with('error', 'Certificate file not found.');
        }

        return response()->download($filePath, $certificate->certificate_number . '.pdf');
    }
}
