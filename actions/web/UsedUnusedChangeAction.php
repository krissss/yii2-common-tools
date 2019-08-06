<?php

namespace kriss\actions\web;

use kriss\actions\web\crud\ToggleAction;
use kriss\enum\UsedUnusedStatus;

/**
 * in actions
 * $actions['change-status'] => [
 *     'class' => UsedUnusedChangeAction::class,
 *     'modelClass' => User::class,
 * ]
 */
class UsedUnusedChangeAction extends ToggleAction
{
    public $attribute = 'status';
    public $onValue = UsedUnusedStatus::NORMAL;
    public $offValue = UsedUnusedStatus::DISABLE;
}
