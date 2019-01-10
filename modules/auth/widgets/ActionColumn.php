<?php

namespace kriss\modules\auth\widgets;

use kriss\modules\auth\tools\AuthValidate;

class ActionColumn extends \kriss\widgets\ActionColumn
{
    protected function renderButton($button, $model, $key, $index)
    {
        if (isset($button['action'])) {
            $button['visible'] = AuthValidate::checkRoute($button['action']);
        }

        return parent::renderButton($button, $model, $key, $index);
    }
}
