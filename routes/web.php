<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminParticipantController;
use App\Http\Controllers\AdminCertificateController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventTypeController;
use App\Http\Controllers\Admin\AdminEventOrganizerController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CertificateVerificationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

// Public routes
Route::get('/', function () {
    return redirect()->route('events.index');
});

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Event registration routes (both guest and authenticated users)
Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])
    ->name('events.register');

// Public certificate verification routes
Route::get('/certificates/verify/{hash?}', [CertificateVerificationController::class, 'show'])
    ->name('certificates.verify');
Route::post('/certificates/verify', [CertificateVerificationController::class, 'verify'])
    ->name('certificates.verify.api');
Route::post('/certificates/search', [CertificateVerificationController::class, 'search'])
    ->name('certificates.search');

// CSRF token refresh route
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
})->name('csrf-token');

// Debug session route (remove in production)
Route::get('/debug-session', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token(),
        'session_driver' => config('session.driver'),
        'session_lifetime' => config('session.lifetime'),
        'user_id' => Auth::id(),
        'session_data' => session()->all()
    ]);
})->name('debug-session');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/my-events', [EventRegistrationController::class, 'index'])->name('my-events.index');
    Route::get('/my-events/{registration}', [EventRegistrationController::class, 'show'])->name('my-events.show');

    // Certificate routes
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Event management
    Route::post('events/{event}/update', [AdminEventController::class, 'update'])->name('events.update-with-files');
    Route::resource('events', AdminEventController::class);

    // User management
    Route::resource('users', AdminUserController::class);

    // Event types and organizers management
    Route::resource('event-types', AdminEventTypeController::class);
    Route::resource('event-organizers', AdminEventOrganizerController::class);

    // Participant management
    Route::get('participants', [AdminParticipantController::class, 'index'])->name('participants.index');
    Route::get('participants/{registration}', [AdminParticipantController::class, 'show'])->name('participants.show');
    Route::post('participants/{registration}/approve', [AdminParticipantController::class, 'approve'])->name('participants.approve');
    Route::post('participants/{registration}/reject', [AdminParticipantController::class, 'reject'])->name('participants.reject');
    Route::delete('participants/{registration}', [AdminParticipantController::class, 'destroy'])->name('participants.destroy');
    Route::post('participants/bulk-approve', [AdminParticipantController::class, 'bulkApprove'])->name('participants.bulk-approve');
    Route::post('participants/bulk-reject', [AdminParticipantController::class, 'bulkReject'])->name('participants.bulk-reject');
    Route::post('participants/bulk-destroy', [AdminParticipantController::class, 'bulkDestroy'])->name('participants.bulk-destroy');
    Route::get('events/{event}/attendance-report', [AdminParticipantController::class, 'attendanceReport'])->name('participants.attendance-report');

    // Registration approval management
    Route::get('registrations', [\App\Http\Controllers\Admin\AdminRegistrationController::class, 'index'])->name('registrations.index');
    Route::get('registrations/{registration}', [\App\Http\Controllers\Admin\AdminRegistrationController::class, 'show'])->name('registrations.show');
    Route::post('registrations/{registration}/approve', [\App\Http\Controllers\Admin\AdminRegistrationController::class, 'approve'])->name('registrations.approve');
    Route::post('registrations/{registration}/reject', [\App\Http\Controllers\Admin\AdminRegistrationController::class, 'reject'])->name('registrations.reject');
    Route::post('registrations/bulk-approve', [\App\Http\Controllers\Admin\AdminRegistrationController::class, 'bulkApprove'])->name('registrations.bulk-approve');
    Route::post('events/{event}/approve-all-registrations', [\App\Http\Controllers\Admin\AdminRegistrationController::class, 'bulkApproveByEvent'])->name('events.approve-all-registrations');

    // Check-in/QR scanning
    Route::get('check-in', [CheckInController::class, 'index'])->name('check-in.index');
    Route::post('check-in/scan', [CheckInController::class, 'scan'])->name('check-in.scan');

    // Debug routes - remove after testing
    Route::get('debug-qr/{qr}', function ($qr) {
        $registration = \App\Models\EventRegistration::where('qr_code', $qr)->with(['user', 'event'])->first();
        return response()->json([
            'qr_searched' => $qr,
            'found' => $registration ? true : false,
            'registration' => $registration ? [
                'user' => $registration->user->name,
                'event' => $registration->event->title,
            ] : null,
            'all_qr_codes' => \App\Models\EventRegistration::pluck('qr_code')->toArray()
        ]);
    });

    Route::get('debug-checkin/{qr}', function ($qr) {
        $registration = \App\Models\EventRegistration::where('qr_code', $qr)
            ->with(['event', 'user', 'checkIn'])
            ->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code.',
                'step' => 'registration_lookup'
            ]);
        }

        if ($registration->isCheckedIn()) {
            return response()->json([
                'success' => false,
                'message' => 'Already checked in.',
                'step' => 'already_checked_in',
                'registration' => [
                    'user' => $registration->user->name,
                    'event' => $registration->event->title,
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ready to check in!',
            'step' => 'ready_to_checkin',
            'registration' => [
                'user' => $registration->user->name,
                'event' => $registration->event->title,
                'is_checked_in' => $registration->isCheckedIn(),
            ]
        ]);
    });

    Route::get('debug-full-checkin/{qr}', function ($qr) {
        try {
            $registration = \App\Models\EventRegistration::where('qr_code', $qr)
                ->with(['event', 'user', 'checkIn'])
                ->first();

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid QR code.',
                    'step' => 'registration_lookup'
                ]);
            }

            if ($registration->isCheckedIn()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Already checked in.',
                    'step' => 'already_checked_in'
                ]);
            }

            // Check if user is authenticated
            if (!\Illuminate\Support\Facades\Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                    'step' => 'auth_check'
                ]);
            }

            // Try to create check-in
            $checkIn = \App\Models\CheckIn::create([
                'event_id' => $registration->event_id,
                'event_registration_id' => $registration->id,
                'checked_in_by' => \Illuminate\Support\Facades\Auth::id(),
                'checked_in_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully checked in!',
                'step' => 'check_in_created',
                'check_in_id' => $checkIn->id,
                'registration' => [
                    'user' => $registration->user->name,
                    'event' => $registration->event->title,
                    'checked_in_at' => $checkIn->checked_in_at->format('Y-m-d H:i:s'),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'step' => 'exception_caught',
                'error_type' => get_class($e)
            ]);
        }
    });

    // Test PDF generation - remove after testing
    Route::get('test-pdf', function () {
        try {
            $certificate = \App\Models\Certificate::first();
            if (!$certificate) {
                return 'No certificates found. Please create one first.';
            }

            $pdfService = new \App\Services\CertificatePdfService();
            $filename = $pdfService->generatePdf($certificate);
            return 'PDF generated successfully: ' . $filename;
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage() . '<br>Line: ' . $e->getLine() . '<br>File: ' . $e->getFile();
        }
    });

    Route::get('test-download', function () {
        try {
            $certificate = \App\Models\Certificate::first();
            if (!$certificate) {
                return 'No certificates found.';
            }

            // Generate PDF if not exists
            if (!$certificate->is_generated) {
                $pdfService = new \App\Services\CertificatePdfService();
                $pdfService->generatePdf($certificate);
                $certificate->refresh();
            }

            $filePath = storage_path('app/public/' . $certificate->certificate_path);

            if (!file_exists($filePath)) {
                return 'File not found at: ' . $filePath;
            }

            return response()->download($filePath, $certificate->certificate_number . '.pdf');
        } catch (\Exception $e) {
            return 'Download Error: ' . $e->getMessage();
        }
    });

    Route::get('events/{event}/check-ins', [CheckInController::class, 'eventCheckIns'])->name('events.check-ins');

    // Certificate management
    Route::resource('certificates', AdminCertificateController::class);
    Route::get('certificates/{certificate}/download', [AdminCertificateController::class, 'download'])->name('certificates.download-admin');
    Route::get('certificates/{certificate}/preview', [AdminCertificateController::class, 'preview'])->name('certificates.preview');
    Route::post('events/{event}/generate-certificates', [AdminCertificateController::class, 'generateForEvent'])->name('events.generate-certificates');
    Route::patch('certificates/{certificate}/mark-generated', [AdminCertificateController::class, 'markAsGenerated'])->name('certificates.mark-generated');
    Route::post('certificates/assign-participant', [AdminCertificateController::class, 'assignToParticipant'])->name('certificates.assign-participant');
});

require __DIR__ . '/auth.php';
