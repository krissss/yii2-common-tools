<?php

namespace kriss\actions\rest\crud;

use kriss\actions\helper\ActionTools;
use yii\base\Action;
use yii\base\UnknownPropertyException;

abstract class AbstractAction extends Action
{
    private $_unDefinedAttributes = [];

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

    public function __set($name, $value)
    {
        try {
            parent::__set($name, $value);
        } catch (UnknownPropertyException $e) {
            $this->_unDefinedAttributes[$name] = $value;
        }
    }

    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (UnknownPropertyException $e) {
            if (isset($this->_unDefinedAttributes[$name])) {
                return $this->_unDefinedAttributes[$name];
            }
            throw $e;
        }
    }
}
