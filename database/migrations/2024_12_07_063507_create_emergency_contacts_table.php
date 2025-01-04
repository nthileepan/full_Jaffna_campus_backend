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
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('relationship')->nullable();
            $table->longText('address')->nullable();
            $table->longText('address_line')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->integer('postal_code')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_contacts');
    }
};
