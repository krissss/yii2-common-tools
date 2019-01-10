<?php

namespace kriss\modules\auth\widgets;

use kriss\modules\auth\tools\AuthValidate;

class ToggleColumn extends \kriss\widgets\ToggleColumn
{
    protected function checkCanOperate($model, $key, $index)
    {
        if (!AuthValidate::checkRoute($this->action)) {
            return false;
        }
        return parent::checkCanOperate($model, $key, $index);
    }
}
