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
        Schema::table('event_registrations', function (Blueprint $table) {
            // For guest registrations (non-users)
            $table->string('guest_name')->nullable()->after('status');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('guest_student_id')->nullable()->after('guest_email');
            $table->enum('guest_course', ['BSIT', 'BSIS', 'BLIS', 'BSEMC'])->nullable()->after('guest_student_id');
            $table->enum('guest_year_level', ['1st Year', '2nd Year', '3rd Year', '4th Year'])->nullable()->after('guest_course');
            $table->string('guest_section')->nullable()->after('guest_year_level');

            // Make user_id nullable to support guest registrations
            $table->foreignId('user_id')->nullable()->change();

            // Add unique constraints for guest registrations only
            $table->unique(['event_id', 'guest_email'], 'unique_event_guest_email');
            $table->unique(['event_id', 'guest_student_id'], 'unique_event_guest_student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            // Drop the new unique constraints
            $table->dropUnique('unique_event_guest_email');
            $table->dropUnique('unique_event_guest_student_id');

            $table->dropColumn([
                'guest_name',
                'guest_email',
                'guest_student_id',
                'guest_course',
                'guest_year_level',
                'guest_section'
            ]);

            // Revert user_id to not nullable
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
