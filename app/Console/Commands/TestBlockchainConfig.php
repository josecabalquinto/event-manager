<?php

namespace App\Console\Commands;

use App\Services\BlockchainCertificateService;
use Illuminate\Console\Command;

class TestBlockchainConfig extends Command
{
    protected $signature = 'blockchain:test-config';
    protected $description = 'Test blockchain configuration and service';

    public function handle()
    {
        $this->info('Testing blockchain configuration...');

        // Test environment variables
        $this->info('Environment Variables:');
        $this->line('AMOY_RPC_URL: ' . env('AMOY_RPC_URL'));
        $this->line('BLOCKCHAIN_CONTRACT_ADDRESS: ' . env('BLOCKCHAIN_CONTRACT_ADDRESS'));
        $this->line('BLOCKCHAIN_WALLET_ADDRESS: ' . env('BLOCKCHAIN_WALLET_ADDRESS'));
        $this->line('Has BLOCKCHAIN_PRIVATE_KEY: ' . (env('BLOCKCHAIN_PRIVATE_KEY') ? 'Yes' : 'No'));

        // Test config values
        $this->info('');
        $this->info('Config Values:');
        $this->line('amoy_rpc_url: ' . config('blockchain.amoy_rpc_url'));
        $this->line('contract_address: ' . config('blockchain.contract_address'));
        $this->line('wallet_address: ' . config('blockchain.wallet_address'));
        $this->line('Has private_key: ' . (config('blockchain.private_key') ? 'Yes' : 'No'));

        // Test service instantiation
        try {
            $service = new BlockchainCertificateService();
            $this->info('');
            $this->info('✅ BlockchainCertificateService instantiated successfully');
        } catch (\Exception $e) {
            $this->error('');
            $this->error('❌ Failed to instantiate BlockchainCertificateService: ' . $e->getMessage());
        }

        return 0;
    }
}
