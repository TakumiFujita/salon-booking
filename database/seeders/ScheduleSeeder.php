<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::now(); // 現在の日付
        $endDate = Carbon::now()->addWeeks(4); // 4週間分を作成

        Schedule::generateSchedule($startDate, $endDate);
    }
}
