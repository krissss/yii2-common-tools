<?php

namespace kriss\actions\rest\crud;

use kriss\actions\helper\ActionTools;
use yii\base\Action;

abstract class AbstractAction extends Action
{
    /**
     * @var string|callable
     */
    public $beforeRunCallback;

    public function runWithParams($params)
    {
        $args = $this->controller->bindActionParams($this, $params);
        if ($this->beforeRunCallback) {
            if ($this->beforeRunCallback) {
                ActionTools::invokeClassMethod($this->controller, $this->beforeRunCallback, ...$args);
            }
        }
        return parent::runWithParams($params);
    }
}
