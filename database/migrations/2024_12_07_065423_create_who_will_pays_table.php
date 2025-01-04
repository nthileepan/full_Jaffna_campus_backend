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
        Schema::create('who_will_pays', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->string('name')->nullable();
            $table->longText('address')->nullable();
            $table->longText('address_official')->nullable();
            $table->string('city')->nullable();
            $table->string('Province')->nullable();
            $table->integer('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->boolean('scholarship')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('who_will_pays');
    }
};
