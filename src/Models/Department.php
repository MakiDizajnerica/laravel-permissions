<?php

namespace MakiDizajnerica\Permissions\Models;

use Illuminate\Database\Eloquent\Model;
use MakiDizajnerica\Permissions\Support\Department\HasPermissions;
use MakiDizajnerica\Permissions\Support\Department\HasAssignedUsers;
use MakiDizajnerica\Permissions\Support\Department\HasCustomOptions;
use MakiDizajnerica\Permissions\Support\Department\ScopeDepartments;
use MakiDizajnerica\Permissions\Support\Department\InteractsWithAssignedUsers;

final class Department extends Model
{
    use InteractsWithAssignedUsers,
    HasAssignedUsers,
    ScopeDepartments,
    HasCustomOptions,
    HasPermissions;

    protected $table = 'departments';
    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
        'usable',
        'editable',
        'hidden',
        'notify',
        'note',
    ];

    protected $hidden = [
        //
    ];

    protected $appends = [
        //
    ];

    protected $casts = [
        'usable' => 'boolean',
        'editable' => 'boolean',
        'hidden' => 'boolean',
        'notify' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
