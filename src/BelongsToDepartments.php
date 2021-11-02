<?php

namespace MakiDizajnerica\Permissions;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use MakiDizajnerica\Permissions\Models\Department;
use MakiDizajnerica\Permissions\Contracts\HasCacheablePermissions as HasCacheablePermissionsContract;

trait BelongsToDepartments
{
    /**
     * The Departments that model belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    protected function departments()
    {
        return $this->belongsToMany(Department::class, 'department_user');
    }



    /**
     * @return void
     */
    private function clearCachedPermissionsIfPosible()
    {
        if ($this instanceof HasCacheablePermissionsContract) {
            $this->clearCachedPermissions();
        }
    }



    /**
     * @param  array $departments
     * @return array|null
     */
    private function getAllDepartmentIds(array $departments)
    {
        if (empty($departments)) {
            return;
        }

        $departments = Department::scopeUsableNotHidden()->select('id')->whereIn(
            'slug', Arr::flatten($departments)
        )->get();

        if ($departments->isNotEmpty()) {
            $this->clearCachedPermissionsIfPosible();
        }

        return $departments->modelKeys();
    }



    /**
     * Assign model to Department.
     * 
     * @param  \MakiDizajnerica\Permissions\Models\Department|int $department
     * @return $this
     */
    public function assignToDepartment($department)
    {
        if ($department instanceof Department) {
            $department = $department->id;
        }

        $this->clearCachedPermissionsIfPosible();

        $this->departments()->attach($department);

        return $this;
    }

    /**
     * Assign model to Departments.
     * 
     * @param  mixed $departments
     * @return $this
     */
    public function assignToDepartments(...$departments)
    {
        $this->departments()->attach(
            $this->getAllDepartmentIds($departments)
        );

        return $this;
    }

    /**
     * Remove model from Departments.
     * 
     * @param  mixed $departments
     * @return $this
     */
    public function removeFromDepartments(...$departments)
    {
        $this->departments()->detach(
            $this->getAllDepartmentIds($departments)
        );

        return $this;
    }

    /**
     * Sync Departments.
     * 
     * @param  mixed $departments
     * @return $this
     */
    public function syncDepartments(...$departments)
    {
        $this->departments()->sync(
            $this->getAllDepartmentIds($departments)
        );

        return $this;
    }

    /**
     * Remove model from all Departments.
     * 
     * @return $this
     */
    public function removeFromAllDepartments()
    {
        $this->clearCachedPermissionsIfPosible();

        $this->departments()->detach();

        return $this;
    }

    /**
     * Check if model belongs to Department.
     *
     * @param  string $department
     * @return bool
     */
    public function belongsToDepartment($department) : bool
    {
        return $this->departments
            ->pluck('slug')
            ->contains($department);
    }



    /**
     * @return string
     * 
     * @throws \InvalidArgumentException
     */
    private function adminDepartment()
    {
        $name = config('permissions.admin_department');

        if (empty($name) || ! is_string($name)) {
            throw new InvalidArgumentException(
                'Config property "admin_department" must be defined.'
            );
        }

        return $name;
    }

    /**
     * Check if model is administrator.
     *
     * @return bool
     */
    public function isAdministrator()
    {
        return $this->belongsToDepartment($this->adminDepartment());
    }

    /**
     * Scope models that are in administration department.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdministrators($query)
    {
        return $this->scopeInDepartment($query, $this->adminDepartment());
    }

    /**
     * Scope models that are not in administration department.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotAdministrators($query)
    {
        return $query->whereDoesntHave('departments', function ($query) {
            $query->where('slug', $this->adminDepartment());
        });
    }

    /**
     * Scope models that are in specific department.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $department
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInDepartment($query, $department)
    {
        return $query->whereHas('departments', function ($query) use ($department) {
            $query->where('slug', $department);
        });
    }
}
