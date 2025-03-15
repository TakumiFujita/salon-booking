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
        $startDate = Carbon::now()->addWeeks(5)->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();

        Schedule::generateSchedule($startDate, $endDate);
    }
}
