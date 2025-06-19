<?php

namespace App\Policies;

use App\Models\User;
use App\Utils\HandlesPolicies;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class UserPolicy
{
    use HandlesPolicies;

    protected string $model = User::class;

    protected function isOwn(User $user, Model $model): bool
    {
        return $model instanceof User && $user->getKey() === $model->getKey();
    }
}
