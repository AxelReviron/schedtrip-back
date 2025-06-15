<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Permission;
use App\Models\Role;

return new class extends Migration
{
    private array $actionsMap = [
        'v' =>   'view',
        'c' =>   'create',
        'u' =>   'update',
        'd' =>   'delete',
        'vo' =>  'view_own',
        'uo' =>  'update_own',
        'do' =>  'delete_own',
    ];

    public function up(): void
    {
        foreach (config('permission.rolesModelsPermissions') as $roleName => $resources) {
            $roleModel = $this->createRoles($roleName);

            foreach ($resources as $resource => $actions) {
                $permissionsNames = $this->formatPermissionName($resource, $actions);

                foreach ($permissionsNames as $permissionName) {
                    $permission = $this->createPermissions($permissionName);
                    $this->attachPermissions($roleModel, $permission);
                }
            }
        }
        foreach (config('permission.customPermissions') as $roleName => $permissions) {
            $role = $this->createRoles($roleName);
            foreach ($permissions as $permissionName) {
                $permission = $this->createPermissions($permissionName);
                $this->attachPermissions($role, $permission);
            }
        }
    }

    private function createRoles(string $role): Role
    {
        return Role::firstOrCreate(['name' => $role]);
    }

    private function formatPermissionName(string $resource, string $actions): array
    {
        $actions = explode(',', $actions);
        $permissionsNames = [];
        foreach ($actions as $action) {
            $permissionsNames[] = $this->actionsMap[$action] . '_' . $resource;
        }
        return $permissionsNames;
    }

    private function createPermissions(string $permission): Permission
    {
        return Permission::firstOrCreate(['name' => $permission]);
    }

    private function attachPermissions(Role $role, Permission $permission): void
    {
        $role->givePermissionTo($permission);
    }
};
