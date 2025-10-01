<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminParticipantController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\CheckInController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get('/', function () {
    return redirect()->route('events.index');
});

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Guest registration routes
Route::middleware('guest')->group(function () {
    Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])
        ->name('events.register');
});

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
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    // Event management
    Route::resource('events', AdminEventController::class);
    
    // User management
    Route::resource('users', AdminUserController::class);
    
    // Participant management
    Route::get('participants', [AdminParticipantController::class, 'index'])->name('participants.index');
    Route::get('participants/{registration}', [AdminParticipantController::class, 'show'])->name('participants.show');
    
    // Check-in/QR scanning
    Route::get('check-in', [CheckInController::class, 'index'])->name('check-in.index');
    Route::post('check-in/scan', [CheckInController::class, 'scan'])->name('check-in.scan');
    Route::get('events/{event}/check-ins', [CheckInController::class, 'eventCheckIns'])->name('events.check-ins');
});

require __DIR__.'/auth.php';
