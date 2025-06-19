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
        if ($model->is_public) return true;

        $resource = $this->getModelName();
        $canViewBase = $user->can('view_' . $resource) || ($user->can("view_own_$resource") && $this->isOwn($user, $model));

        if ($canViewBase) return true;

        return $model->participants()
            ->where('user_id', $user->getKey())
            ->whereIn('permission', ['view', 'update'])
            ->exists();
    }

    public function update(User $user, Model $model): bool
    {
        $resource = $this->getModelName();
        $canUpdateBase = $user->can('update_' . $resource) || ($user->can("update_own_$resource") && $this->isOwn($user, $model));

        if ($canUpdateBase) return true;

        return $model->participants()
            ->where('user_id', $user->getKey())
            ->where('permission', 'update')
            ->exists();
    }

    public function manageParticipants(User $user, Model $model): bool
    {
        $resource = $this->getModelName();

        return $user->can("update_own_$resource") && $this->isOwn($user, $model);
    }
}
