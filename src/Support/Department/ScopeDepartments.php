<?php

namespace MakiDizajnerica\Permissions\Support\Department;

use InvalidArgumentException;

trait ScopeDepartments
{
    /**
     * @return string
     * 
     * @throws \InvalidArgumentException
     */
    private function adminDepartmentName()
    {
        $name = config('permissions.admin_department_name');

        if (empty($name) || ! is_string($name)) {
            throw new InvalidArgumentException(
                'Config property "admin_department_name" must be defined.'
            );
        }

        return $name;
    }



    /**
     * Scope usable Departments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsable($query)
    {
        return $query->where('usable', 1);
    }

    /**
     * Scope usable and not hidden Departments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsableNotHidden($query)
    {
        return $query->where([
            ['usable', '=', 1],
            ['hidden', '=', 0],
        ]);
    }

    /**
     * Scope admin Department.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdministration($query)
    {
        return $query->where('slug', $this->adminDepartmentName());
    }

    /**
     * Scope Departments that are not admin.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotAdministration($query)
    {
        return $query->where('slug', '<>', $this->adminDepartmentName());
    }

    /**
     * Scope Departments that are usable, not hidden and not admin.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsableNotHiddenNotAdministration($query)
    {
        return $query->where([
            ['slug', '<>', $this->adminDepartmentName()],
            ['usable', '=', 1],
            ['hidden', '=', 0],
        ]);
    }
}
