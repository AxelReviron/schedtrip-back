<?php

namespace App\Utils;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HandlesPolicies
{
    /**
     * @throws Exception
     */
    protected function getModelName(): string
    {
        if (!isset($this->model)) {
            throw new Exception('Model not set');
        }

        return Str::plural(strtolower(class_basename($this->model)));
    }

    /**
     * @throws Exception
     */
    protected function isOwn(User $user, Model $model): bool
    {
        throw new Exception('isOwn() method should be implemented in the Resource Policy');
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view_' . $this->getModelName());
    }

    public function view(User $user, Model $model): bool
    {
        $resource = $this->getModelName();

        return $user->can('view_' . $resource) || ($user->can("view_own_$resource") && $this->isOwn($user, $model));
    }

    public function create(User $user): bool
    {
        return $user->can('create_' . $this->getModelName());
    }

    public function update(User $user, Model $model): bool
    {
        $resource = $this->getModelName();

        return $user->can('update_' . $resource) || ($user->can("update_own_$resource") && $this->isOwn($user, $model));
    }

    public function delete(User $user, Model $model): bool
    {
        $resource = $this->getModelName();

        return $user->can('delete_' . $resource) || ($user->can("delete_own_$resource") && $this->isOwn($user, $model));
    }

    public function restore(User $user, Model $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, Model $model): bool
    {
        return false;
    }

}
