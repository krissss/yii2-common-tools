<?php

namespace kriss\modules\auth\widgets;

use kriss\modules\auth\tools\AuthValidate;

class YiiActionColumn extends \yii\grid\ActionColumn
{
    public function init()
    {
        parent::init();

        foreach ($this->buttons as $action => $button) {
            if (!AuthValidate::checkRoute($action)) {
                unset($action);
            }
        }
    }
}
