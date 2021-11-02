<?php

namespace MakiDizajnerica\Permissions\Models;

use Illuminate\Database\Eloquent\Model;
use MakiDizajnerica\Permissions\Support\Permission\ScopePermissions;

final class Permission extends Model
{
    use ScopePermissions;

    protected $table = 'permissions';
    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'usable',
    ];

    protected $hidden = [
        //
    ];

    protected $appends = [
        //
    ];

    protected $casts = [
        'usable' => 'boolean',
    ];
}
