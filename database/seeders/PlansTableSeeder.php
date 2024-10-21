<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Plan::create(
            [
                'name' => 'Free Plan',
                'price' => 0,
                'duration' => 'Lifetime',
                'max_stores' => 2,
                'max_products' => 50,
                'max_users' => 10,
                'storage_limit' => 1024,
                'enable_custdomain' => 'on',
                'enable_custsubdomain' => 'on',
                'pwa_store' => 'on',
                'enable_chatgpt' => 'on',
                'shipping_method' => 'on',
                'description' => 'For companies that need a robust full-featured time tracker.',
                'image' => 'free_plan.png',
            ]
        );
    }
}
