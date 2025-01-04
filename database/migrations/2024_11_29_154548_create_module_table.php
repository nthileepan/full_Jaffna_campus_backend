<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
{
    if (!Schema::hasTable('module')) {
        Schema::create('module', function (Blueprint $table) {
            $table->id('module_id');
            $table->string('module_name');
            $table->string('department_shortname');
            $table->string('module_hours');
            $table->enum('module_status', ['Active', 'Inactive']);
            $table->timestamps();
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module');
    }
};
