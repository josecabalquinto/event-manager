<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Certificate;
use Carbon\Carbon;

class CertificateDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the test event
        $event = Event::where('title', 'Certificate Test Event')->first();

        if (!$event) {
            $this->command->error('Test event not found. Please run CertificateTestSeeder first.');
            return;
        }

        // Get registrations with check-ins but no certificates
        $registrations = $event->registrations()
            ->whereHas('checkIn')
            ->whereDoesntHave('certificate')
            ->with(['user', 'checkIn'])
            ->get();

        $certificatesCreated = 0;

        foreach ($registrations as $registration) {
            Certificate::create([
                'event_registration_id' => $registration->id,
                'certificate_number' => 'CERT-' . strtoupper(substr(md5(uniqid()), 0, 8)) . '-' . date('Y'),
                'participant_name' => $registration->user->name,
                'event_title' => $event->title,
                'event_date' => $event->event_date,
                'completion_date' => $registration->checkIn->checked_in_at->toDateString(),
                'certificate_template' => $event->certificate_template,
                'is_generated' => false,
            ]);
            $certificatesCreated++;
        }

        $this->command->info("Created {$certificatesCreated} certificates for checked-in participants.");
    }
}
