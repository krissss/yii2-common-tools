<?php

namespace kriss\actions\traits;

use yii\web\Controller;

/**
 * @property bool $isAjax 是否通过 ajax 展示 view，默认为 true
 * @property string $view 视图名，默认为 action 的 id
 */
trait AjaxViewTrait
{
    /**
     * @param $controller Controller
     * @param $params
     * @return mixed
     */
    public function render($controller, $params)
    {
        $isAjax = isset($this->isAjax) ? $this->isAjax : true;
        $view = isset($this->view) ? $this->view : $controller->action->id;

        $renderMethod = $isAjax ? 'renderAjax' : 'render';
        return $controller->$renderMethod($view, $params);
    }
}
