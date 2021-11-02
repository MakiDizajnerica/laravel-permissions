<?php

namespace MakiDizajnerica\Permissions\Contracts;

interface HasPermissions
{
    /**
     * Give permissions.
     * 
     * @param  \MakiDizajnerica\Permissions\Models\Permission|int $permission
     * @return $this
     */
    public function givePermission($permission);

    /**
     * Give set of permissions.
     * 
     * @param  mixed $permissions
     * @return $this
     */
    public function givePermissions(...$permissions);

    /**
     * Remove set of permissions.
     * 
     * @param  mixed $permissions
     * @return $this
     */
    public function removePermissions(...$permissions);

    /**
     * Sync permissions.
     * 
     * @param  mixed $permissions
     * @return $this
     */
    public function syncPermissions(...$permissions);

    /**
     * Remove all permissions.
     * 
     * @return $this
     */
    public function removeAllPermissions();

    /**
     * Check if model has permission.
     *
     * @param  string $permissions
     * @return bool
     */
    public function hasPermissionTo($permission) : bool;

    /**
     * Check if permission is present through Departments.
     *
     * @param  string $permissions
     * @return bool
     */
    public function hasPermissionThroughDepartmentsTo($permission) : bool;
}
