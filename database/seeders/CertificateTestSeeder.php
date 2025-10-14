<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\CheckIn;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CertificateTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users if they don't exist
        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $users[] = User::firstOrCreate([
                'email' => "participant{$i}@example.com"
            ], [
                'name' => "Test Participant {$i}",
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
            ]);
        }

        // Create a test event with certificate enabled
        $event = Event::firstOrCreate([
            'title' => 'Certificate Test Event'
        ], [
            'description' => 'This is a test event for certificate generation.',
            'event_date' => Carbon::yesterday(), // Past event
            'event_time' => '10:00:00',
            'location' => 'Test Venue',
            'latitude' => 14.5995,
            'longitude' => 120.9842,
            'max_participants' => 50,
            'is_published' => true,
            'has_certificate' => true,
            'certificate_title' => 'Certificate of Completion',
            'certificate_description' => 'This certificate is awarded to participants who successfully completed the Certificate Test Event.',
            'certificate_template' => 'This is to certify that {participant_name} has successfully completed {event_title} on {event_date}. We acknowledge their dedication and participation.',
            'certificate_background_color' => '#ffffff',
            'certificate_text_color' => '#000000',
            'certificate_border_style' => 'classic',
            'certificate_signatories' => [
                ['name' => 'John Doe', 'title' => 'Event Coordinator'],
                ['name' => 'Jane Smith', 'title' => 'Director of Programs']
            ],
        ]);

        // Register users for the event
        foreach ($users as $user) {
            $registration = EventRegistration::firstOrCreate([
                'user_id' => $user->id,
                'event_id' => $event->id
            ], [
                'qr_code' => Str::uuid(),
            ]);

            // Check in some participants (but not all, so we have variety)
            if (rand(0, 1)) {
                $admin = User::where('is_admin', true)->first();
                CheckIn::firstOrCreate([
                    'event_registration_id' => $registration->id
                ], [
                    'event_id' => $event->id,
                    'checked_in_by' => $admin->id,
                    'checked_in_at' => Carbon::yesterday()->addHours(rand(1, 8)),
                ]);
            }
        }

        // Create another event (upcoming) to test event selection
        Event::firstOrCreate([
            'title' => 'Upcoming Workshop'
        ], [
            'description' => 'An upcoming workshop for testing purposes.',
            'event_date' => Carbon::tomorrow(),
            'event_time' => '14:00:00',
            'location' => 'Future Venue',
            'latitude' => 14.5995,
            'longitude' => 120.9842,
            'max_participants' => 30,
            'is_published' => true,
            'has_certificate' => true,
            'certificate_title' => 'Workshop Completion Certificate',
            'certificate_description' => 'Awarded for workshop completion.',
            'certificate_template' => 'This certifies that {participant_name} has completed the {event_title} workshop.',
            'certificate_background_color' => '#f8fafc',
            'certificate_text_color' => '#1f2937',
            'certificate_border_style' => 'modern',
            'certificate_signatories' => [
                ['name' => 'Alice Johnson', 'title' => 'Workshop Leader']
            ],
        ]);

        $this->command->info('Certificate test data seeded successfully!');
    }
}
