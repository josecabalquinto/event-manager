<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use App\Models\EventRegistration;
use App\Models\CheckIn;
use App\Models\Event;
use App\Models\User;
use App\Services\BlockchainCertificateService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TestStoreMethod extends Command
{
    protected $signature = 'blockchain:test-store';
    protected $description = 'Test the store method for certificate generation with blockchain';

    public function handle()
    {
        $this->info('Testing AdminCertificateController::store method...');

        // Create test user
        $user = User::create([
            'name' => 'Test User ' . time(),
            'email' => 'test' . time() . '@example.com',
            'password' => bcrypt('password')
        ]);

        // Create test registration
        $registration = EventRegistration::create([
            'event_id' => 1,
            'user_id' => $user->id,
            'qr_code' => Str::uuid(),
            'status' => 'registered'
        ]);

        // Create check-in
        CheckIn::create([
            'event_id' => 1,
            'event_registration_id' => $registration->id,
            'checked_in_by' => 1,
            'checked_in_at' => now()
        ]);

        $this->info("Created registration ID: {$registration->id}");

        // Simulate the store method logic
        $event = Event::find(1);
        $certificateTemplate = $event->getDefaultCertificateTemplate();
        $blockchainService = new BlockchainCertificateService();

        $this->info('Creating certificate using store method logic...');
        $certificate = Certificate::create([
            'event_registration_id' => $registration->id,
            'participant_name' => $registration->user->name,
            'event_title' => $event->title,
            'event_date' => $event->event_date,
            'completion_date' => now(),
            'certificate_template' => $certificateTemplate,
        ]);

        $this->info("Created certificate ID: {$certificate->id}");

        // Issue on blockchain (like the fixed store method)
        $this->info('Issuing on blockchain...');
        $blockchainResult = $blockchainService->issueCertificate($certificate);

        $this->info('Blockchain issuance result:');
        $this->line('  - success: ' . ($blockchainResult['success'] ? 'true' : 'false'));
        if ($blockchainResult['success']) {
            $this->line('  - tx_hash: ' . $blockchainResult['tx_hash']);
            $this->line('  - certificate_hash: ' . $blockchainResult['certificate_hash']);
        } else {
            $this->line('  - error: ' . $blockchainResult['error']);
        }

        // Check final certificate state
        $certificate->refresh();
        $this->info('Final certificate blockchain details:');
        $this->line("  - certificate_hash: " . ($certificate->certificate_hash ?? 'null'));
        $this->line("  - blockchain_tx_hash: " . ($certificate->blockchain_tx_hash ?? 'null'));
        $this->line("  - blockchain_address: " . ($certificate->blockchain_address ?? 'null'));
        $this->line("  - is_blockchain_verified: " . ($certificate->is_blockchain_verified ? 'true' : 'false'));

        return 0;
    }
}
