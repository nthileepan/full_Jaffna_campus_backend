<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id(); // Primary key with AUTO_INCREMENT
            $table->string('prefix')->nullable();
            $table->string('department_name', 100);
            $table->string('batch_name');
            $table->date('batch_short_date');
            $table->date('batch_end_date');
            $table->string('batch_status');
            $table->timestamps(); // Includes created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batches');
    }
}
