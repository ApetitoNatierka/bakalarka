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
        Schema::table('medical_treatments', function (Blueprint $table) {
            $table->unsignedBigInteger('medical_examination_id')->nullable();
            $table->foreign('medical_examination_id')->references('id')->on('medical_examinations')->onDelete('set null');
            $table->unsignedBigInteger('animal_id')->nullable();
            $table->foreign('animal_id')->references('id')->on('animals')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_treatments', function (Blueprint $table) {
            $table->dropForeign(['medical_examination_id']);
            $table->dropColumn('medical_examination_id');
            $table->dropForeign(['animal_id']);
            $table->dropColumn('animal_id');
        });
    }
};
