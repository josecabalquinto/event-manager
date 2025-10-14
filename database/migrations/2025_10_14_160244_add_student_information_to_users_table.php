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
        Schema::table('users', function (Blueprint $table) {
            $table->string('student_id')->nullable()->after('email');
            $table->enum('course', ['BSIT', 'BSIS', 'BLIS', 'BSEMC'])->nullable()->after('student_id');
            $table->enum('year_level', ['1st Year', '2nd Year', '3rd Year', '4th Year'])->nullable()->after('course');
            $table->string('section')->nullable()->after('year_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['student_id', 'course', 'year_level', 'section']);
        });
    }
};
