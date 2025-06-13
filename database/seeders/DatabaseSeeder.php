<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TripSeeder::class,
            StopSeeder::class,
            NoteSeeder::class,
            LuggageSeeder::class,
            ItemSeeder::class,
        ]);
    }
}
