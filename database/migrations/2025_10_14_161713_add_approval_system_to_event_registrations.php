<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First update existing 'registered' status to 'approved'
        DB::table('event_registrations')->where('status', 'registered')->update(['status' => 'approved']);

        Schema::table('event_registrations', function (Blueprint $table) {
            // Change status to enum with approval states
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();

            // Add approval fields
            $table->foreignId('approved_by')->nullable()->constrained('users')->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('rejection_reason')->nullable()->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            // Remove approval fields
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approved_by', 'approved_at', 'rejection_reason']);

            // Revert status to string with old default
            $table->string('status')->default('registered')->change();
        });
    }
};
