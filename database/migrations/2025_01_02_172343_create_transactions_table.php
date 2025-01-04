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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->enum('payment_type_id',['MonthlyInstallment', 'UniPayment']);
            $table->date('date');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_by', ['Card', 'Cash']);
            $table->string('invoice_no')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['Pending', 'Completed', 'Failed']);
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
