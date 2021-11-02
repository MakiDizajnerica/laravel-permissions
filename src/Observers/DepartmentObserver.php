<?php

namespace MakiDizajnerica\Permissions\Observers;

use Illuminate\Support\Str;
use MakiDizajnerica\Permissions\Models\Department;

class DepartmentObserver
{
    /**
     * Handle the Department "creating" event.
     *
     * @param  \MakiDizajnerica\Permissions\Models\Department $department
     * @return void
     */
    public function creating(Department $department)
    {
        $department->name = Str::title(Str::lower($department->name));
        $department->slug = Str::slug($department->name);
    }

    /**
     * Handle the Department "created" event.
     *
     * @param  \MakiDizajnerica\Permissions\Models\Department $department
     * @return void
     */
    public function created(Department $department)
    {
        //
    }

    /**
     * Handle the Department "updating" event.
     *
     * @param  \MakiDizajnerica\Permissions\Models\Department $department
     * @return void
     */
    public function updating(Department $department)
    {
        $department->name = Str::title(Str::lower($department->name));
        $department->slug = Str::slug($department->name);
    }

    /**
     * Handle the Department "updated" event.
     *
     * @param  \MakiDizajnerica\Permissions\Models\Department $department
     * @return void
     */
    public function updated(Department $department)
    {
        //
    }

    /**
     * Handle the Department "deleting" event.
     *
     * @param  \MakiDizajnerica\Permissions\Models\Department $department
     * @return void
     */
    public function deleting(Department $department)
    {
        //
    }

    /**
     * Handle the Department "deleted" event.
     *
     * @param  \MakiDizajnerica\Permissions\Models\Department $department
     * @return void
     */
    public function deleted(Department $department)
    {
        //
    }
}
