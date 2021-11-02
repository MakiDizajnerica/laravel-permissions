<?php

namespace MakiDizajnerica\Permissions;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use MakiDizajnerica\Permissions\Models\Permission;
use MakiDizajnerica\Permissions\Contracts\BelongsToDepartments as BelongsToDepartmentsContract;

trait HasCacheablePermissions
{
    /**
     * @return string
     */
    private function cacheKey()
    {
        return sprintf(
            '%s-%s:permissions', $this->getTable(), $this->getKey()
        );
    }

    /**
     * Clear model's cached Permissions.
     *
     * @return void
     */
    public function clearCachedPermissions()
    {
        Cache::forget($this->cacheKey());
    }



    /**
     * The Permissions that belong to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    protected function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user');
    }



    /**
     * Get all available Permission's ids.
     * 
     * @param  array $permissions
     * @return array|null
     */
    private function getAllPermissionIds(array $permissions)
    {
        if (empty($permissions)) {
            return;
        }

        $permissions = Permission::select('id')->usable()->whereIn(
            'slug', Arr::flatten($permissions)
        )->get();

        if ($permissions->isNotEmpty()) {
            $this->clearCachedPermissions();
        }

        return $permissions->modelKeys();
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

        $this->clearCachedPermissions();

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
        $this->clearCachedPermissions();

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
        return $this->allPermissions()
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
        return $this->hasPermissionTo($permission);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function allPermissions()
    {
        return Cache::rememberForever($this->cacheKey(), function () {
            $permissions = $this->permissions;

            if ($this instanceof BelongsToDepartmentsContract) {
                $departments = $this->departments;

                foreach ($departments as $department) {
                    $permissions = $permissions->merge(
                        $department->permissions
                    );
                }
            }

            return $permissions->unique();
        });
    }
}
