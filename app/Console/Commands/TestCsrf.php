<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCsrf extends Command
{
    protected $signature = 'test:csrf';
    protected $description = 'Test CSRF token generation';

    public function handle()
    {
        $this->info('Testing CSRF token generation...');

        // Test CSRF token generation
        $token = csrf_token();
        $this->line("Generated CSRF token: {$token}");

        // Test session configuration
        $this->info('Session Configuration:');
        $this->line('Driver: ' . config('session.driver'));
        $this->line('Lifetime: ' . config('session.lifetime') . ' minutes');
        $this->line('Cookie: ' . config('session.cookie'));
        $this->line('Domain: ' . (config('session.domain') ?? 'null'));
        $this->line('Path: ' . config('session.path'));
        $this->line('Secure: ' . (config('session.secure') ? 'true' : 'false'));
        $this->line('Same Site: ' . config('session.same_site'));

        // Check session storage
        $this->info('Session Storage:');
        if (config('session.driver') === 'file') {
            $sessionPath = storage_path('framework/sessions');
            $this->line("Session Path: {$sessionPath}");
            $this->line('Path exists: ' . (is_dir($sessionPath) ? 'Yes' : 'No'));
            $this->line('Path writable: ' . (is_writable($sessionPath) ? 'Yes' : 'No'));
        } else {
            $this->line('Using database sessions');
        }

        return 0;
    }
}
