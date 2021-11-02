<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Model Class
    |--------------------------------------------------------------------------
    |
    | Here you may define class that will represent the user who will be given
    | permisions and who will be able to get assigned to the departments.
    |
    */

    'user_model' => \App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | User Lookup Field
    |--------------------------------------------------------------------------
    |
    | Here you may define the field that users will be searched for when
    | assigning to the department.
    |
    */

    'users_lookup_field' => 'id',

    /*
    |--------------------------------------------------------------------------
    | Admin Department Name
    |--------------------------------------------------------------------------
    |
    | Here you may define the name of the main/admin department.
    | Users assigned to this department will have all permissions.
    |
    */

    'admin_department' => 'administration',

];
