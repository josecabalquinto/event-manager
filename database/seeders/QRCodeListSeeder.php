<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventRegistration;

class QRCodeListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registrations = EventRegistration::with(['user', 'event', 'checkIn'])->get();

        $this->command->info('Available QR Codes for Testing:');
        $this->command->info('=====================================');

        foreach ($registrations as $registration) {
            $checkedIn = $registration->checkIn ? '✓ Checked In' : '✗ Not Checked In';
            $this->command->info(
                'QR: ' . $registration->qr_code .
                    ' | User: ' . $registration->user->name .
                    ' | Event: ' . $registration->event->title .
                    ' | Status: ' . $checkedIn
            );
        }

        $this->command->info('');
        $this->command->info('Copy any QR code above to test the check-in system!');
    }
}
