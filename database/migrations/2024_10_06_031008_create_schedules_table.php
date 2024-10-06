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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stylist_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('status', ['available', 'booked', 'unavailable'])->default('available');
            $table->timestamps();

            // 外部キー制約
            $table->foreign('stylist_id')->references('id')->on('stylists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
