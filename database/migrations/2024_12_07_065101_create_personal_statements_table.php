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
        Schema::create('personal_statements', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->longText('course_reason')->nullable();
            $table->boolean('self')->nullable();
            $table->boolean('parents')->nullable();
            $table->boolean('spouse')->nullable();
            $table->boolean('other')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_statements');
    }
};
