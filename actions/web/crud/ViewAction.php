<?php

namespace kriss\actions\web\crud;

use kriss\actions\traits\AjaxViewTrait;
use kriss\actions\traits\ModelClassActionTrait;

class ViewAction extends AbstractAction
{
    use ModelClassActionTrait;
    use AjaxViewTrait;

    /**
     * @var string
     */
    public $beforeRenderCallback;
    /**
     * @var string
     */
    public $view = '_view';

    public function run($id)
    {
        $model = $this->findModel($id, $this->controller);

        $this->beforeRenderCallback && call_user_func($this->beforeRenderCallback, $model);

        return $this->render($this->controller, [
            'model' => $model
        ]);
    }
}
