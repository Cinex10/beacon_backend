<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Beacon;
use App\Models\Store;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(30)->create();
        Admin::create([
            "name" => "Yassine",
            "email" => "admin@gmail.com",
            'password' => bcrypt('admin'),
        ]);
        $config = file_get_contents(base_path('storage/systemConfig.json'));
        $configData = json_decode($config);
        $points = $configData->pointsPerStore;
        Store::create([
            "name" => "AB Store",
            "email" => "abstore@gmail.com",
            'points' => $points,
            'address' => 'Imama 89, Tlemcen',
            'password' => bcrypt('store'),
        ]);
        Store::create([
            "name" => "Bora Store",
            "email" => "borastore@gmail.com",
            "storecode" => "abstore",
            'points' => $points,
            'address' => 'La macta 45, Sidi Bel Abbes',
            'password' => bcrypt('store'),
        ]);

        User::create([
            "name" => "Yassine",
            "gender" => "male",
            "email" => "yaciniyac@gmail.com",
            'password' => bcrypt('store'),
        ]);


        Beacon::create([
            'idStore' => 1,
            'idStore' => 1,
            'identifier' => 'idddd',
            'proximityUUID' => '151bc3a3-e50a-4cd4-857f-e9e97db94460'
        ]);

        Voucher::create([
            'idStore' => 1,
            'idBeacon' => 1,
            'description' => 'desc',
            'nbCustomers' => 2,
            'terms' => 'alpa\\ndsds',
        ]);




        // $users = Store::factory()->count(3)->create();
    }
}
