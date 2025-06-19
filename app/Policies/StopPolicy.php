<?php

namespace App\Policies;

use App\Models\Stop;
use App\Models\User;
use App\Utils\HandlesPolicies;
use Illuminate\Database\Eloquent\Model;

class StopPolicy
{
    use HandlesPolicies;

    protected string $model = Stop::class;

    protected function isOwn(User $user, Model $model): bool
    {
        return $model instanceof Stop && $user->getKey() === $model->trip->author->getKey();
    }

    public function view(User $user, Model $model): bool
    {
        if ($model->trip->is_public) return true;

        $resource = $this->getModelName();
        $canViewBase = $user->can('view_' . $resource) || ($user->can("view_own_$resource") && $this->isOwn($user, $model));

        if ($canViewBase) return true;

        return $model->trip->participants()
            ->where('user_id', $user->getKey())
            ->whereIn('permission', ['view', 'update'])
            ->exists();
    }

    public function update(User $user, Model $model): bool
    {
        $resource = $this->getModelName();
        $canUpdateBase = $user->can('update_' . $resource) || ($user->can("update_own_$resource") && $this->isOwn($user, $model));

        if ($canUpdateBase) return true;

        return $model->trip->participants()
            ->where('user_id', $user->getKey())
            ->where('permission', 'update')
            ->exists();
    }
}
