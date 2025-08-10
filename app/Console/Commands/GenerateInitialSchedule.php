<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use Carbon\Carbon;

class GenerateInitialSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-initial-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初回実行時、現在の週を含む4週間分のスケジュールを作成';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startDate = Carbon::now()->startOfWeek(); // 今週月曜
        $endDate = $startDate->copy()->addDays(27); // 4週間分

        $this->info("初回スケジュール作成: {$startDate->toDateString()} ～ {$endDate->toDateString()}");

        Schedule::generateSchedule($startDate, $endDate);
    }
}
