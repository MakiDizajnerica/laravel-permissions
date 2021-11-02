<?php

namespace MakiDizajnerica\Permissions\Support\Department;

use Illuminate\Support\Facades\Notification;

trait InteractsWithAssignedUsers
{
    /**
     * The models that belong to the Department.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany($this->userModel(), 'department_user');
    }

    /**
     * Notify models that belong to Department.
     *
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function notifyUsers($notification)
    {
        if ($this->shouldNotifyUsers()) {
            with($this->users, function ($users) use ($notification) {
                if ($users->isNotEmpty()) {
                    Notification::send($users, $notification);
                }
            });
        }
    }
}
