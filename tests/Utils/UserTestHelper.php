<?php

namespace Tests\Utils;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

trait UserTestHelper
{
    public function createUsersWithDefaultPermissions(int $number): User|Collection
    {
        $users = User::factory()->count($number)->create();
        $users->each(function (User $user) {
            $user->assignRole('user');
        });

        return $number === 1 ? $users->first() : $users;
    }
}
