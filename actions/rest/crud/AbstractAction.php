<?php

namespace kriss\actions\rest\crud;

use kriss\actions\traits\ToolsTrait;
use yii\base\Action;

abstract class AbstractAction extends Action
{
    use ToolsTrait;

    /**
     * @var string|callable
     */
    public $beforeRunCallback;

    public function runWithParams($params)
    {
        $args = $this->controller->bindActionParams($this, $params);
        if ($this->beforeRunCallback) {
            if ($this->beforeRunCallback) {
                $this->invokeClassMethod($this->controller, $this->beforeRunCallback, ...$args);
            }
        }
        return parent::runWithParams($params);
    }
}
