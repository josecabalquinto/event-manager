<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('certificate_hash')->nullable()->after('certificate_path');
            $table->string('blockchain_tx_hash')->nullable()->after('certificate_hash');
            $table->string('blockchain_address')->nullable()->after('blockchain_tx_hash');
            $table->boolean('is_blockchain_verified')->default(false)->after('blockchain_address');
            $table->timestamp('blockchain_issued_at')->nullable()->after('is_blockchain_verified');

            $table->index(['certificate_hash']);
            $table->index(['blockchain_tx_hash']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropIndex(['certificate_hash']);
            $table->dropIndex(['blockchain_tx_hash']);
            $table->dropColumn([
                'certificate_hash',
                'blockchain_tx_hash',
                'blockchain_address',
                'is_blockchain_verified',
                'blockchain_issued_at'
            ]);
        });
    }
};
