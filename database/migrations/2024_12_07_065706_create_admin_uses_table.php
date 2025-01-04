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
        Schema::create('admin_uses', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->string('student_number')->nullable();
            $table->string('total_fees')->nullable();
            $table->string('registration_fees')->nullable();
            $table->string('installment')->nullable();
            $table->string('discount')->nullable();
            $table->date('join_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_uses');
    }
};
