<?php

namespace Database\Seeders;

use App\Models\Stop;
use App\Models\Trip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trips = Trip::all();

        foreach ($trips as $index => $trip) {
            Stop::factory()
                ->count(rand(1, 5))
                ->for($trip, 'trip')
                ->create([
                    'order_index' => $index
                ]);
        }
    }
}
