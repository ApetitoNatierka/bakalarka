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
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('organisation_id')->nullable();
            $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('set null');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['organisation_id']);
            $table->dropColumn('organisation_id');
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
