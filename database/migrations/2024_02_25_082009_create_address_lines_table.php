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
        Schema::create('address_lines', function (Blueprint $table) {
            $table->id();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->string('street');
            $table->string('house_number');
            $table->string('city');
            $table->string('region');
            $table->string('postal_code');
            $table->string('country');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address_lines');
    }
};
