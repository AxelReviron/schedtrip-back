<?php

namespace App\Policies;

use App\Models\User;
use App\Utils\HandlesPolicies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserPolicy
{
    use HandlesPolicies;

    protected string $model = User::class;

    protected function isOwn(User $user, Model $model): bool
    {
        return $model instanceof User && $user->getKey() === $model->getKey();
    }

    public function view(User $user, Model $model): bool
    {
        if (!$this->isOwn($user, $model) && $this->isBlocked($user, $model)) {
            return false;
        }

        $resource = $this->getModelName();

        return $user->can('view_' . $resource) || ($user->can("view_own_$resource") && $this->isOwn($user, $model));
    }

    protected function isBlocked(User $user, Model $target): bool
    {
        if (!$target instanceof User) {
            return false;
        }

        return DB::table('user_friend')
            ->where('from_user_id', $user->getKey())
            ->where('to_user_id', $target->getKey())
            ->where('status', 'blocked')
            ->exists();
    }

}
