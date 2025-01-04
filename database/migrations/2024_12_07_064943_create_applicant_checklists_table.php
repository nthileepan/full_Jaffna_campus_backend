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
        Schema::create('applicant_checklists', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->boolean('newspaper')->nullable();
            $table->boolean('seminar')->nullable();
            $table->boolean('social_media')->nullable();
            $table->boolean('open_events')->nullable();
            $table->boolean('bcas_website')->nullable();
            $table->boolean('leaflets')->nullable();
            $table->boolean('student_review')->nullable();
            $table->boolean('radio')->nullable();
            $table->boolean('other')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_checklists');
    }
};
