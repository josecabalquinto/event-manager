<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use App\Services\BlockchainCertificateService;
use Illuminate\Console\Command;

class TestSpecificCertificate extends Command
{
    protected $signature = 'blockchain:test-specific {id?}';
    protected $description = 'Test blockchain certificate issuance for specific certificate ID';

    public function handle()
    {
        $certificateId = $this->argument('id') ?: 2;
        $this->info("Testing blockchain certificate issuance for ID: {$certificateId}");

        $certificate = Certificate::find($certificateId);
        if (!$certificate) {
            $this->error("Certificate with ID {$certificateId} not found");
            return 1;
        }

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
