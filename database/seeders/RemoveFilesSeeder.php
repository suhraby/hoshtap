<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RemoveFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $directory = storage_path('app/public');

        if (File::exists($directory)) {
            File::cleanDirectory($directory);
            $this->command->info('All files and folders inside storage/app/public/ have been removed.');
        } else {
            $this->command->warn('The storage/app/public/ directory does not exist.');
        }
    }
}
