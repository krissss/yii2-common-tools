<?php

namespace kriss\actions\web\crud;

use kriss\traits\WebControllerTrait;

abstract class AbstractAction extends \kriss\actions\rest\crud\AbstractAction
{
    use WebControllerTrait;

    /**
     * 跳转到前一个页面
     */
    public function redirectPrevious()
    {
        return $this->actionPreviousRedirect($this->controller);
    }
}
