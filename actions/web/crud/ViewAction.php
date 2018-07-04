<?php

namespace kriss\actions\web\crud;

class ViewAction extends AbstractAction
{
    /**
     * @var string
     */
    public $beforeRenderCallback;
    /**
     * @var bool
     */
    public $isAjax = true;
    /**
     * @var string
     */
    public $view = '_view';

    public function run($id)
    {
        $model = $this->findModel($id);

        $this->beforeRenderCallback && call_user_func($this->beforeRenderCallback, $model);

        $renderMethod = $this->isAjax ? 'renderAjax' : 'render';
        return $this->controller->$renderMethod($this->view, [
            'model' => $model
        ]);
    }
}
