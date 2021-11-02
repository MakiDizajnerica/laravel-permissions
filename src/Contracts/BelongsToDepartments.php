<?php

namespace MakiDizajnerica\Permissions\Contracts;

interface BelongsToDepartments
{
    /**
     * Assign model to Department.
     * 
     * @param  \MakiDizajnerica\Permissions\Models\Department|int $department
     * @return $this
     */
    public function assignToDepartment($department);

    /**
     * Assign model to Departments.
     * 
     * @param  mixed $departments
     * @return $this
     */
    public function assignToDepartments(...$departments);

    /**
     * Remove model from Departments.
     * 
     * @param  mixed $departments
     * @return $this
     */
    public function removeFromDepartments(...$departments);

    /**
     * Sync Departments.
     * 
     * @param  mixed $departments
     * @return $this
     */
    public function syncDepartments(...$departments);

    /**
     * Remove model from all Departments.
     * 
     * @return $this
     */
    public function removeFromAllDepartments();

    /**
     * Check if model belongs to Department.
     *
     * @param  string $department
     * @return bool
     */
    public function belongsToDepartment($department) : bool;
}
