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
            $table->boolean('has_certificate')->default(false);
            $table->text('certificate_template')->nullable();
            $table->string('certificate_title')->nullable();
            $table->text('certificate_description')->nullable();
            $table->string('certificate_background_color')->default('#ffffff');
            $table->string('certificate_text_color')->default('#000000');
            $table->string('certificate_border_style')->default('classic'); // classic, modern, elegant
            $table->json('certificate_signatories')->nullable(); // Store signatory names and titles
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'has_certificate',
                'certificate_template',
                'certificate_title',
                'certificate_description',
                'certificate_background_color',
                'certificate_text_color',
                'certificate_border_style',
                'certificate_signatories'
            ]);
        });
    }
};
