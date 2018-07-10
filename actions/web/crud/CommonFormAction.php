<?php

namespace kriss\actions\web\crud;

use Yii;

class CommonFormAction extends AbstractAction
{
    /**
     * @var string
     */
    public $doMethod;
    /**
     * @var string
     */
    public $view;
    /**
     * @var string|array
     */
    public $successRedirect;

    public function run()
    {
        $model = $this->newModel();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $result = $this->doMethodOrCallback($this->doMethod, $model, $model);
            if ($result !== false && $this->successRedirect) {
                return $this->controller->redirect($this->successRedirect);
            }
        }

        return $this->controller->render($this->view ?: $this->controller->action->id, [
            'model' => $model
        ]);
    }
}
