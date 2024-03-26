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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('surname');
            $table->string('last_name');
            $table->string('position');
            $table->date('birth_date');
            $table->date('start_date');
            $table->string('identification_number');
            $table->string('department')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('salary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
