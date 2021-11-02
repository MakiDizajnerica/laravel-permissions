<?php

namespace MakiDizajnerica\Permissions;

use Illuminate\Support\Arr;
use MakiDizajnerica\Permissions\Models\Permission;
use MakiDizajnerica\Permissions\Contracts\BelongsToDepartments as BelongsToDepartmentsContract;

trait HasPermissions
{
    private $permissionsFromDepartments;

    /**
     * The Permissions that belong to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user');
    }



    /**
     * @param  array $permissions
     * @return array|null
     */
    private function getAllPermissionIds(array $permissions)
    {
        if (empty($permissions)) {
            return;
        }

        return Permission::select('id')->usable()->whereIn(
            'slug', Arr::flatten($permissions)
        )->get()->modelKeys();
    }



    /**
     * Give Permissions.
     * 
     * @param  \MakiDizajnerica\Permissions\Models\Permission|int $permission
     * @return $this
     */
    public function givePermission($permission)
    {
        if ($permission instanceof Permission) {
            $permission = $permission->id;
        }

        $this->permissions()->attach($permission);

        return $this;
    }

    /**
     * Give set of Permissions.
     * 
     * @param  mixed $permissions
     * @return $this
     */
    public function givePermissions(...$permissions)
    {
        $this->permissions()->attach(
            $this->getAllPermissionIds($permissions)
        );

        return $this;
    }

    /**
     * Remove set of Permissions.
     * 
     * @param  mixed $permissions
     * @return $this
     */
    public function removePermissions(...$permissions)
    {
        $this->permissions()->detach(
            $this->getAllPermissionIds($permissions)
        );

        return $this;
    }

    /**
     * Sync Permissions.
     * 
     * @param  mixed $permissions
     * @return $this
     */
    public function syncPermissions(...$permissions)
    {
        $this->permissions()->sync(
            $this->getAllPermissionIds($permissions)
        );

        return $this;
    }

    /**
     * Remove all Permissions.
     * 
     * @return $this
     */
    public function removeAllPermissions()
    {
        $this->permissions()->detach();

        return $this;
    }

    /**
     * Check if model has Permission.
     *
     * @param  string $permissions
     * @return bool
     */
    public function hasPermissionTo($permission) : bool
    {
        return $this->permissions
            ->pluck('slug')
            ->contains($permission);
    }



    /**
     * Check if permission is present through Departments.
     *
     * @param  string $permissions
     * @return bool
     */
    public function hasPermissionThroughDepartmentsTo($permission) : bool
    {
        return $this->permissionsFromDepartments()
            ->pluck('slug')
            ->contains($permission);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function permissionsFromDepartments()
    {
        if ($this->permissionsFromDepartments) {
            return $this->permissionsFromDepartments;
        }

        $permissions = collect();

        if (! ($this instanceof BelongsToDepartmentsContract)) {
            return $permissions;
        }

        $departments = $this->departments;

        foreach ($departments as $department) {
            $permissions = $permissions->merge(
                $department->permissions
            );
        }

        $this->permissionsFromDepartments = $permissions->unique();
    }
}
