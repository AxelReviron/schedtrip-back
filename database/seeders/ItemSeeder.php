<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Luggage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manyLuggage = Luggage::all();

        foreach ($manyLuggage as $luggage) {
            Item::factory()
                ->count(rand(15, 20))
                ->for($luggage, 'luggage')
                ->create();
        }
    }
}
