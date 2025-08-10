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
        Schema::create('default_stylist_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stylist_id')->constrained()->onDelete('cascade'); // スタイリストID
            $table->unsignedTinyInteger('weekday'); // 0（日曜）〜6（土曜）
            $table->dateTime('start_time'); // 出勤開始日時
            $table->dateTime('end_time');   // 出勤終了日時
            $table->enum('status', ['available', 'unavailable'])->default('available'); // 勤務状態
            $table->timestamps();
            $table->unique(['stylist_id', 'weekday']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_stylist_schedules');
    }
};
