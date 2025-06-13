<?php

namespace Database\Seeders;

use App\Models\Luggage;
use App\Models\Trip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LuggageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trips = Trip::all();

        foreach ($trips as $trip) {
            $user = $trip->author;

            Luggage::factory()
                ->for($trip, 'trip')
                ->for($user, 'user')
                ->create();
        }
    }
}
