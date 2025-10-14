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
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('event_type_id')->nullable()->constrained('event_types')->onDelete('set null');
            $table->foreignId('event_organizer_id')->nullable()->constrained('event_organizers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['event_type_id']);
            $table->dropForeign(['event_organizer_id']);
            $table->dropColumn(['event_type_id', 'event_organizer_id']);
        });
    }
};
