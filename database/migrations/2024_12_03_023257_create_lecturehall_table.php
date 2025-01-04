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
        Schema::create('lecturehall', function (Blueprint $table) {
            $table->id('lecturehall_id');
            $table->string('lecturehall_name'); 
            $table->string('lecturehall_shortname'); 
            $table->enum('lecturehall_status', ['Active', 'Inactive']); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturehall');
    }
};
