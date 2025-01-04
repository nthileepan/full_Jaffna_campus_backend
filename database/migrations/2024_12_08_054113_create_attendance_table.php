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
        Schema::create('attendance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('lecture_id');
            $table->dateTime('attendance_at');
            $table->unsignedBigInteger('time_table_id');
            $table->unsignedBigInteger('otp_used')->nullable();
            $table->enum('verification_type', ['OTP', 'QR', 'lecture']);
            $table->enum('status', ['present', 'absent']);
            $table->timestamps();

            $table->index('otp_used', 'attendance_otp_used_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance');
    }

};
