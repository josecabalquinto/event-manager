<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use App\Services\BlockchainCertificateService;
use Illuminate\Console\Command;

class TestCertificateBlockchain extends Command
{
    protected $signature = 'blockchain:test-certificate';
    protected $description = 'Test blockchain certificate issuance';

    public function handle()
    {
        $this->info('Testing blockchain certificate issuance...');

        $certificate = Certificate::first();
        if (!$certificate) {
            $this->error('No certificates found in database');
            return 1;
        }

        $this->info("Testing with certificate ID: {$certificate->id}");
        $this->info("Current blockchain details:");
        $this->line("  - certificate_hash: " . ($certificate->certificate_hash ?? 'null'));
        $this->line("  - blockchain_tx_hash: " . ($certificate->blockchain_tx_hash ?? 'null'));
        $this->line("  - blockchain_address: " . ($certificate->blockchain_address ?? 'null'));
        $this->line("  - is_blockchain_verified: " . ($certificate->is_blockchain_verified ? 'true' : 'false'));

        $service = new BlockchainCertificateService();
        $result = $service->issueCertificate($certificate);

        $this->info('Blockchain issuance result:');
        $this->line('  - success: ' . ($result['success'] ? 'true' : 'false'));
        if ($result['success']) {
            $this->line('  - tx_hash: ' . $result['tx_hash']);
            $this->line('  - certificate_hash: ' . $result['certificate_hash']);
        } else {
            $this->line('  - error: ' . $result['error']);
        }

        // Reload certificate to see updates
        $certificate->refresh();
        $this->info('Updated certificate blockchain details:');
        $this->line("  - certificate_hash: " . ($certificate->certificate_hash ?? 'null'));
        $this->line("  - blockchain_tx_hash: " . ($certificate->blockchain_tx_hash ?? 'null'));
        $this->line("  - blockchain_address: " . ($certificate->blockchain_address ?? 'null'));
        $this->line("  - is_blockchain_verified: " . ($certificate->is_blockchain_verified ? 'true' : 'false'));

        return 0;
    }
}
