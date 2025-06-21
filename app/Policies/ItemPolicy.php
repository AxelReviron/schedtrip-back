<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use App\Utils\HandlesPolicies;
use Illuminate\Database\Eloquent\Model;

class ItemPolicy
{
    use HandlesPolicies;

    protected string $model = Item::class;

    protected function isOwn(User $user, Model $model): bool
    {
        return $model instanceof Item && $user->getKey() === $model->luggage->user->getKey();
    }
}
