<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\CheckIn;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test event with certificate enabled
        $event = Event::create([
            'title' => 'Web Development Workshop 2025',
            'description' => 'Learn modern web development techniques with Laravel and Vue.js',
            'event_date' => '2025-01-15',
            'event_time' => '09:00:00',
            'location' => 'Tech Hub Conference Center',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'max_participants' => 50,
            'is_published' => true,
            // Certificate configuration
            'has_certificate' => true,
            'certificate_title' => 'Web Development Workshop Completion Certificate',
            'certificate_description' => 'Certificate of completion for successfully finishing the Web Development Workshop',
            'certificate_template' => 'This is to certify that {participant_name} has successfully completed the {event_title} workshop on {event_date}. The participant has demonstrated proficiency in modern web development technologies including Laravel and Vue.js.',
            'certificate_background_color' => '#ffffff',
            'certificate_text_color' => '#1f2937',
            'certificate_border_style' => 'elegant',
            'certificate_signatories' => [
                ['name' => 'John Smith', 'title' => 'Workshop Director'],
                ['name' => 'Jane Doe', 'title' => 'Technical Lead']
            ],
        ]);

        // Get the test user
        $user = User::where('email', 'user@example.com')->first();

        if ($user && $event) {
            // Create registration
            $registration = EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'status' => 'confirmed',
            ]);

            // Create check-in
            CheckIn::create([
                'event_registration_id' => $registration->id,
                'event_id' => $event->id,
                'checked_in_by' => User::where('email', 'admin@example.com')->first()->id,
                'checked_in_at' => now(),
            ]);

            // Create certificate
            Certificate::create([
                'event_registration_id' => $registration->id,
                'participant_name' => $user->name,
                'event_title' => $event->title,
                'event_date' => $event->event_date,
                'completion_date' => now()->toDateString(),
                'is_generated' => true,
                'certificate_path' => 'certificates/sample-certificate.pdf',
            ]);
        }
    }
}
