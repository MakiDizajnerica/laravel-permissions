<?php

namespace MakiDizajnerica\Permissions\Support\Department;

trait HasCustomOptions
{
    /**
     * Check if Department is usable.
     *
     * @return bool
     */
    public function isUsable()
    {
        return $this->usable;
    }

    /**
     * Check if Department is hidden.
     *
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * Check if Department is editable.
     *
     * @return bool
     */
    public function isEditable()
    {
        return ! $this->isHidden() &&
            $this->isUsable() &&
            $this->editable;
    }

    /**
     * Check if Department's users should be notified.
     *
     * @return bool
     */
    public function shouldNotifyUsers()
    {
        return $this->notify;
    }
}
