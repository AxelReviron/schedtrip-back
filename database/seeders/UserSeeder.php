<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(20)
            ->create([
                'password' => Hash::make('password'),
            ]);

//        $user = User::factory()->create([
//            'name' => config('app.admin_name'),
//            'email' => config('app.admin_email'),
//            'password' => Hash::make(config('app.admin_password')),
//        ]);

        //$user->assignRole('admin');
    }
}
