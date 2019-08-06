<?php

namespace kriss\widgets;

use kriss\enum\UsedUnusedStatus;

/**
 * in view columns
 * [
 *    'class' => UsedUnusedStatusColumn::class,
 *    'attribute' => 'status',
 * ]
 */
class UsedUnusedStatusColumn extends ToggleColumn
{
    public $attribute = 'status';
    public $action = 'change-status';
    public $onValue = UsedUnusedStatus::NORMAL;
    public $offValue = UsedUnusedStatus::DISABLE;

    public static function getDefaultItems()
    {
        return UsedUnusedStatus::getViewItems();
    }
}
