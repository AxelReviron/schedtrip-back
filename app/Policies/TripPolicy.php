<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;
use App\Utils\HandlesPolicies;
use Illuminate\Database\Eloquent\Model;

class TripPolicy
{
    use HandlesPolicies;

    protected string $model = Trip::class;

    protected function isOwn(User $user, Model $model): bool
    {
        return $model instanceof Trip && $user->getKey() === $model->author->getKey();
    }

    public function view(User $user, Model $model): bool
    {
        $resource = $this->getModelName();

        $canViewBase = $user->can('view_' . $resource) || ($user->can("view_own_$resource") && $this->isOwn($user, $model));

        if ($canViewBase) {
            return true;
        }

        // TODO: Add this permission in custom route for friend collaboration
        return $user->can("view_trip_{$model->getKey()}") || $user->can("edit_trip_{$model->getKey()}");
    }

    public function update(User $user, Model $model): bool
    {
        $resource = $this->getModelName();

        $canUpdateBase = $user->can('update_' . $resource) || ($user->can("update_own_$resource") && $this->isOwn($user, $model));

        if ($canUpdateBase) {
            return true;
        }

        // TODO: Add this permission in custom route for friend collaboration
        return $user->can("update_trip_{$model->getKey()}");
    }
}
