<?php

namespace kriss\widgets;

use yii\base\Widget;

abstract class BaseViewWidget extends Widget
{
    public function init()
    {
        parent::init();

        ob_start();
        ob_implicit_flush(false);
    }

    public function run()
    {
        return ob_get_clean();
    }
}
