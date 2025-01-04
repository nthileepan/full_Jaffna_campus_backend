<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lecture_id');
            $table->string('otp_value', 255);
            $table->dateTime('generated_at');
            $table->dateTime('expires_at');
            $table->timestamps();
        });

        // Set the AUTO_INCREMENT starting value for `id` column to 19
        DB::statement("ALTER TABLE otps AUTO_INCREMENT = 19;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otps');
    }

};
