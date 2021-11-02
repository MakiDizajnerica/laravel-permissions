<?php

namespace MakiDizajnerica\Permissions\Support\Department;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use MakiDizajnerica\Permissions\Contracts\HasCacheablePermissions as HasCacheablePermissionsContract;

trait HasAssignedUsers
{
    /**
     * @return string
     * 
     * @throws \InvalidArgumentException
     */
    protected function userModel()
    {
        $model = config('permissions.user_model');

        if (empty($model) || ! is_string($model)) {
            throw new InvalidArgumentException(
                'Config property "user_model" must be defined.'
            );
        }

        return $model;
    }



    /**
     * @param  mixed $user
     */
    private function clearCachedPermissionsForUserIfPosible($user)
    {
        $model = $this->userModel();

        if (! ($user instanceof $model)) {
            $user = $model::find($user);
        }

        if ($user instanceof HasCacheablePermissionsContract) {
            $user->clearCachedPermissions();
        }
    }



    /**
     * @param  array $users
     * @return array|null
     * 
     * @throws \InvalidArgumentException
     */
    private function getAllUserIds(array $users)
    {
        if (empty($users)) {
            return;
        }

        $field = config('permissions.users_lookup_field');

        if (empty($field) || ! is_string($field)) {
            throw new InvalidArgumentException(
                'Config property "users_lookup_field" must be defined.'
            );
        }

        $users = $this->userModel()::select('id')->whereIn(
            $field, Arr::flatten($users)
        )->get();

        if ($users->isNotEmpty()) {
            $users->each([$this, 'clearCachedPermissionsForUserIfPosible']);
        }

        return $users->modelKeys();
    }



    /**
     * Assign Users to Department.
     * 
     * @param  mixed $user
     * @return $this
     */
    public function assignUser($user)
    {
        $this->clearCachedPermissionsForUserIfPosible($user);

        $model = $this->userModel();

        if ($user instanceof $model) {
            $user = $user->id;
        }

        $this->users()->attach($user);

        return $this;
    }

    /**
     * Assign Users to Department.
     * 
     * @param  mixed $users
     * @return $this
     */
    public function assignUsers(...$users)
    {
        $this->users()->attach(
            $this->getAllUserIds($users)
        );

        return $this;
    }

    /**
     * Remove Users from Department.
     * 
     * @param  mixed $users
     * @return $this
     */
    public function removeUsers(...$users)
    {
        $this->users()->detach(
            $this->getAllUserIds($users)
        );

        return $this;
    }

    /**
     * Sync Users.
     * 
     * @param  mixed $users
     * @return $this
     */
    public function syncUsers(...$users)
    {
        $this->users()->sync(
            $this->getAllUserIds($users)
        );

        return $this;
    }

    /**
     * Remove all Users.
     * 
     * @return $this
     */
    public function removeAllUsers()
    {
        $this->users->each([$this, 'clearCachedPermissionsForUserIfPosible']);

        $this->users()->detach();

        return $this;
    }
}
