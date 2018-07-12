<?php

namespace kriss\actions\traits;

trait AjaxViewTrait
{
    /**
     * @var bool
     */
    public $isAjax = true;
    /**
     * @var string
     */
    public $view;

    public function render($controller, $params)
    {
        $renderMethod = $this->isAjax ? 'renderAjax' : 'render';
        return $controller->$renderMethod($this->view ?: 'unknown_view', $params);
    }
}
