<?php

namespace MakiDizajnerica\Permissions\Support\Permission;

trait ScopePermissions
{
    /**
     * Scope usable Permissions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsable($query)
    {
        return $query->where('usable', 1);
    }
}
