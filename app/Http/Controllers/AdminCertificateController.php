<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Services\CertificatePdfService;
use App\Services\BlockchainCertificateService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certificates = Certificate::with(['eventRegistration.event', 'eventRegistration.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->through(function ($certificate) {
                return [
                    'id' => $certificate->id,
                    'certificate_number' => $certificate->certificate_number,
                    'participant_name' => $certificate->participant_name,
                    'event_title' => $certificate->event_title,
                    'event_date' => $certificate->event_date->format('Y-m-d'),
                    'completion_date' => $certificate->completion_date->format('Y-m-d'),
                    'is_generated' => $certificate->is_generated,
                    'user' => [
                        'name' => $certificate->eventRegistration->user->name,
                        'email' => $certificate->eventRegistration->user->email,
                    ],
                    'event' => [
                        'id' => $certificate->eventRegistration->event->id,
                        'title' => $certificate->eventRegistration->event->title,
                    ],
                ];
            });

        return Inertia::render('Admin/Certificates/Index', [
            'certificates' => $certificates,
        ]);
    }

    /**
     * Show certificate creation form for specific event
     */
    public function create(Request $request)
    {
        $eventId = $request->get('event_id');
        $event = null;

        if ($eventId) {
            $event = Event::with(['registrations.user', 'registrations.checkIn', 'registrations.certificate'])
                ->findOrFail($eventId);
        }

        $events = Event::published()
            ->with(['registrations.checkIn', 'registrations.certificate'])
            ->orderBy('event_date', 'desc')
            ->get()
            ->map(function ($evt) {
                return [
                    'id' => $evt->id,
                    'title' => $evt->title,
                    'event_date' => $evt->event_date->format('Y-m-d'),
                    'checked_in_count' => $evt->checked_in_participants_count,
                    'certificates_generated_count' => $evt->certificates_generated_count,
                    'has_certificate' => $evt->has_certificate,
                ];
            });

        return Inertia::render('Admin/Certificates/Create', [
            'events' => $events,
            'selectedEvent' => $event ? [
                'id' => $event->id,
                'title' => $event->title,
                'event_date' => $event->event_date->format('Y-m-d'),
                'has_certificate' => $event->has_certificate,
                'certificate_title' => $event->certificate_title,
                'certificate_description' => $event->certificate_description,
                'certificate_template' => $event->getDefaultCertificateTemplate(),
                'certificate_signatories' => $event->certificate_signatories,
                'participants' => $event->registrations
                    ->filter(fn($reg) => $reg->checkIn)
                    ->filter(fn($reg) => !$reg->certificate)
                    ->map(fn($reg) => [
                        'id' => $reg->id,
                        'user_name' => $reg->user->name,
                        'user_email' => $reg->user->email,
                        'checked_in_at' => $reg->checkIn->checked_in_at->format('Y-m-d H:i:s'),
                    ]),
            ] : null,
        ]);
    }

    /**
     * Store certificates for selected participants
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'completion_date' => 'required|date',
            'participants' => 'required|array|min:1',
            'participants.*' => 'required|exists:event_registrations,id',
            'certificate_template' => 'nullable|string',
            'certificate_title' => 'nullable|string|max:255',
            'certificate_description' => 'nullable|string',
        ]);

        $event = Event::findOrFail($request->event_id);
        $generatedCount = 0;
        $blockchainCount = 0;

        // Use custom template if provided, otherwise use event's template
        $certificateTemplate = $request->certificate_template ?: $event->getDefaultCertificateTemplate();
        $blockchainService = new BlockchainCertificateService();

        foreach ($request->participants as $registrationId) {
            $registration = EventRegistration::with(['user', 'checkIn', 'certificate'])
                ->whereHas('checkIn')
                ->whereDoesntHave('certificate')
                ->findOrFail($registrationId);

            $certificate = Certificate::create([
                'event_registration_id' => $registration->id,
                'participant_name' => $registration->user->name,
                'event_title' => $request->certificate_title ?: $event->title,
                'event_date' => $event->event_date,
                'completion_date' => $request->completion_date,
                'certificate_template' => $certificateTemplate,
            ]);

            // Issue certificate on blockchain (this will also generate and save the certificate hash)
            $blockchainResult = $blockchainService->issueCertificate($certificate);
            if ($blockchainResult['success']) {
                $blockchainCount++;
            }

            $generatedCount++;
        }

        $message = "Generated {$generatedCount} certificates for {$event->title}.";
        if ($blockchainCount > 0) {
            $message .= " {$blockchainCount} certificates issued on blockchain.";
        }

        return redirect()->route('admin.certificates.index')
            ->with('success', $message);
    }

    /**
     * Generate certificates for all checked-in participants of an event
     */
    public function generateForEvent(Request $request, Event $event)
    {
        $request->validate([
            'completion_date' => 'required|date',
            'certificate_template' => 'nullable|string',
            'certificate_title' => 'nullable|string|max:255',
        ]);

        if (!$event->has_certificate) {
            return back()->with('error', 'This event is not configured to issue certificates.');
        }

        // Get all checked-in registrations that don't have certificates yet
        $registrations = EventRegistration::where('event_id', $event->id)
            ->whereHas('checkIn')
            ->whereDoesntHave('certificate')
            ->with(['user', 'event'])
            ->get();

        if ($registrations->isEmpty()) {
            return back()->with('warning', 'No eligible participants found for certificate generation.');
        }

        $generatedCount = 0;
        $blockchainCount = 0;
        $certificateTemplate = $request->certificate_template ?: $event->getDefaultCertificateTemplate();
        $blockchainService = new BlockchainCertificateService();

        foreach ($registrations as $registration) {
            $certificate = Certificate::create([
                'event_registration_id' => $registration->id,
                'participant_name' => $registration->user->name,
                'event_title' => $request->certificate_title ?: $event->title,
                'event_date' => $event->event_date,
                'completion_date' => $request->completion_date,
                'certificate_template' => $certificateTemplate,
            ]);

            // Issue certificate on blockchain (this will also generate and save the certificate hash)
            $blockchainResult = $blockchainService->issueCertificate($certificate);
            if ($blockchainResult['success']) {
                $blockchainCount++;
            }

            $generatedCount++;
        }

        $message = "Generated {$generatedCount} certificates for {$event->title}.";
        if ($blockchainCount > 0) {
            $message .= " {$blockchainCount} certificates issued on blockchain.";
        }

        return back()->with('success', $message);
    }

    /**
     * Assign certificate to specific participant
     */
    public function assignToParticipant(Request $request)
    {
        $request->validate([
            'event_registration_id' => 'required|exists:event_registrations,id',
            'completion_date' => 'required|date',
            'certificate_title' => 'nullable|string|max:255',
            'certificate_template' => 'nullable|string',
        ]);

        $registration = EventRegistration::with(['user', 'event', 'checkIn', 'certificate'])
            ->findOrFail($request->event_registration_id);

        if (!$registration->checkIn) {
            return back()->with('error', 'Participant must be checked in before assigning a certificate.');
        }

        if ($registration->certificate) {
            return back()->with('error', 'Certificate already exists for this participant.');
        }

        $certificateTemplate = $request->certificate_template ?: $registration->event->getDefaultCertificateTemplate();

        $certificate = Certificate::create([
            'event_registration_id' => $registration->id,
            'participant_name' => $registration->user->name,
            'event_title' => $request->certificate_title ?: $registration->event->title,
            'event_date' => $registration->event->event_date,
            'completion_date' => $request->completion_date,
            'certificate_template' => $certificateTemplate,
        ]);

        // Issue certificate on blockchain
        $blockchainService = new BlockchainCertificateService();
        $blockchainResult = $blockchainService->issueCertificate($certificate);

        $message = 'Certificate assigned successfully to ' . $registration->user->name;
        if ($blockchainResult['success']) {
            $message .= ' and issued on blockchain.';
        }

        return redirect()->route('admin.certificates.show', $certificate)
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        $certificate->load(['eventRegistration.event', 'eventRegistration.user']);

        return Inertia::render('Admin/Certificates/Show', [
            'certificate' => [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'participant_name' => $certificate->participant_name,
                'event_title' => $certificate->event_title,
                'event_date' => $certificate->event_date->format('Y-m-d'),
                'completion_date' => $certificate->completion_date->format('Y-m-d'),
                'is_generated' => $certificate->is_generated,
                'certificate_url' => $certificate->certificate_url,
                'user' => [
                    'name' => $certificate->eventRegistration->user->name,
                    'email' => $certificate->eventRegistration->user->email,
                ],
                'event' => [
                    'id' => $certificate->eventRegistration->event->id,
                    'title' => $certificate->eventRegistration->event->title,
                    'location' => $certificate->eventRegistration->event->location,
                    'certificate_signatories' => $certificate->eventRegistration->event->certificate_signatories ?? [],
                ],
            ],
        ]);
    }

    /**
     * Generate certificate PDF and mark as generated
     */
    public function markAsGenerated(Certificate $certificate, CertificatePdfService $pdfService)
    {
        try {
            // Load the event relationship to ensure signatories are available
            $certificate->load('event');
            $pdfService->generatePdf($certificate);
            return back()->with('success', 'Certificate PDF generated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating certificate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Preview certificate PDF inline
     */
    public function preview(Certificate $certificate, CertificatePdfService $pdfService)
    {
        try {
            // Load the event relationship to ensure signatories are available
            $certificate->load('event');

            // Generate PDF if it doesn't exist or isn't marked as generated
            if (!$certificate->is_generated || !$certificate->certificate_path) {
                $pdfService->generatePdf($certificate);
                $certificate->refresh(); // Reload the model to get updated data
            }

            $filePath = storage_path('app/public/' . $certificate->certificate_path);

            // If file still doesn't exist after generation attempt, generate it again
            if (!file_exists($filePath)) {
                $pdfService->generatePdf($certificate);
                $certificate->refresh();
                $filePath = storage_path('app/public/' . $certificate->certificate_path);
            }

            // Final check
            if (!file_exists($filePath)) {
                return response()->json(['error' => 'Unable to generate certificate PDF'], 500);
            }

            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $certificate->certificate_number . '.pdf"'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error generating certificate: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download or preview certificate
     */
    public function download(Certificate $certificate, CertificatePdfService $pdfService)
    {
        try {
            // Load the event relationship to ensure signatories are available
            $certificate->load('event');

            // Generate PDF if it doesn't exist or isn't marked as generated
            if (!$certificate->is_generated || !$certificate->certificate_path) {
                $pdfService->generatePdf($certificate);
                $certificate->refresh(); // Reload the model to get updated data
            }

            $filePath = storage_path('app/public/' . $certificate->certificate_path);

            // If file still doesn't exist after generation attempt, generate it again
            if (!file_exists($filePath)) {
                $pdfService->generatePdf($certificate);
                $certificate->refresh();
                $filePath = storage_path('app/public/' . $certificate->certificate_path);
            }

            // Final check
            if (!file_exists($filePath)) {
                return redirect()
                    ->route('admin.certificates.show', $certificate)
                    ->with('error', 'Unable to generate certificate PDF. Please try again.');
            }

            return response()->download($filePath, $certificate->certificate_number . '.pdf');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.certificates.show', $certificate)
                ->with('error', 'Error generating certificate: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        // Delete the certificate file if it exists
        if ($certificate->certificate_path) {
            $filePath = storage_path('app/public/' . $certificate->certificate_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $certificate->delete();

        return back()->with('success', 'Certificate deleted successfully.');
    }
}
