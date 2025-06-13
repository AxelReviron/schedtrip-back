<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\Stop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stops = Stop::all();

        foreach ($stops as $stop) {
            $user = $stop->trip->author;

            Note::factory()
                ->count(rand(0, 4))
                ->for($stop, 'stop')
                ->for($user, 'user')
                ->create();
        }
    }
}
