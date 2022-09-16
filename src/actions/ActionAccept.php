<?php

namespace TaskForce\actions;

use TaskForce\exceptions\ActionUnavailableException;

class ActionAccept extends ActionAbstract
{
    protected $name = 'Принять';
    protected $internal_name = self::ACTION_ACCEPT;

    const ACTION_ACCEPT = 'action_accept';

    public function rightsCheck($user_id): bool
    {
        if ($this->customer_id !== $user_id) {
            return true;
        }
        throw new ActionUnavailableException('Действие вам недоступно');
    }
}
