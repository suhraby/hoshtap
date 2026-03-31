<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('local')) {
            $this->call(RemoveFilesSeeder::class);
        }

        $this->call([
            UserSeeder::class,
            BannerSeeder::class,
            ContactSeeder::class,
            CounterSeeder::class,
            ServiceSeeder::class,
            ManufacturerSeeder::class,
            ClientSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
