<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use Carbon\Carbon;

class GenerateSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '5週間後の週のスケジュールを作成';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 今日から5週間後の週の月曜日を取得
        $startDate = Carbon::now()->addWeeks(4)->startOfWeek();

        // その週の最後（=日曜の終わり）
        $endDate = $startDate->copy()->addDays(6)->endOfDay();

        $this->info("5週間後のスケジュール作成: {$startDate->toDateString()} ～ {$endDate->toDateString()}");

        Schedule::generateSchedule($startDate, $endDate);
    }
}
