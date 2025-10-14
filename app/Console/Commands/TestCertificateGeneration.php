<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use App\Models\EventRegistration;
use App\Models\CheckIn;
use App\Services\BlockchainCertificateService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TestCertificateGeneration extends Command
{
    protected $signature = 'blockchain:test-generation';
    protected $description = 'Test full certificate generation with blockchain';

    public function handle()
    {
        $this->info('Creating test registration...');

        // Create test registration
        $registration = EventRegistration::create([
            'event_id' => 1,
            'user_id' => 1,
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

        // Now test certificate generation like the controller does
        $this->info('Creating certificate...');
        $certificate = Certificate::create([
            'event_registration_id' => $registration->id,
            'participant_name' => 'Test User',
            'event_title' => 'Test Event',
            'event_date' => now(),
            'completion_date' => now(),
            'certificate_template' => 'Test template',
        ]);

        $this->info("Created certificate ID: {$certificate->id}");

        // Test blockchain issuance
        $this->info('Issuing on blockchain...');
        $blockchainService = new BlockchainCertificateService();
        $result = $blockchainService->issueCertificate($certificate);

        $this->info('Blockchain issuance result:');
        $this->line('  - success: ' . ($result['success'] ? 'true' : 'false'));
        if ($result['success']) {
            $this->line('  - tx_hash: ' . $result['tx_hash']);
            $this->line('  - certificate_hash: ' . $result['certificate_hash']);
        } else {
            $this->line('  - error: ' . $result['error']);
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
