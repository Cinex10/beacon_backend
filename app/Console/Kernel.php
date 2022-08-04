<?php

namespace App\Console;

use App\Models\Store;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->everyMinute();
        $schedule->call(function () {
            $stores = Store::all();

            $config = file_get_contents(base_path('storage/systemConfig.json'));
            $configData = json_decode($config);
            $points = $configData->pointsPerStore;

            foreach ($stores as $store) {
                $store->points = $points;
                $store->save();
            }
        })->monthly();
    }



    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
