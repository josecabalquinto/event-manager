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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_registration_id')->constrained()->onDelete('cascade');
            $table->string('certificate_number')->unique();
            $table->string('participant_name');
            $table->string('event_title');
            $table->date('event_date');
            $table->date('completion_date');
            $table->text('certificate_template')->nullable(); // Store custom template or use default
            $table->string('certificate_path')->nullable(); // Generated PDF path
            $table->boolean('is_generated')->default(false);
            $table->timestamps();

            $table->index(['certificate_number']);
            $table->index(['event_registration_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
