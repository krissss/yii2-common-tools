<?php

namespace kriss\modules\auth\widgets;

use kriss\modules\auth\tools\AuthValidate;

class ActionColumn extends \kriss\widgets\ActionColumn
{
    protected function renderButton($button, $model, $key, $index, $defaultVisible = true)
    {
        if (isset($button['action'])) {
            if (!AuthValidate::checkRoute($button['action'])) {
                unset($button['visible']);
            }
        }

        return parent::renderButton($button, $model, $key, $index, false);
    }
}
