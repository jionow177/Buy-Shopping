<?php

namespace Database\Seeders;

use App\Models\Utility;
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
        \Artisan::call('module:migrate LandingPage');
        \Artisan::call('module:seed LandingPage');

        if (\Request::route()->getName() != 'LaravelUpdater::database') {
            $this->call(UsersTableSeeder::class);
            $this->call(PlansTableSeeder::class);
        } else {
            Utility::languagecreate();
        }
    }
}
