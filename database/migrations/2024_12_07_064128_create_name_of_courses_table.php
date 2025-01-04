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
        Schema::create('name_of_courses', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->string('preferred_mode')->nullable();
            $table->string('program_applied_for')->nullable();
            $table->integer('student_number')->nullable();
            $table->string('course_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('name_of_courses');
    }
};
