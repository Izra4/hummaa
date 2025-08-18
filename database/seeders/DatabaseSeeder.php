<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MateriSeeder::class,         // Seeder Materi yang sudah ada
            QuestionSeeder::class,       // Seeder Questions, Tryouts, Materials
            MaterialSeeder::class,      // Seeder untuk kategori materi dan materi
        ]);
    }
}