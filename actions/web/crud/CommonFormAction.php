<?php

namespace kriss\actions\web\crud;

use Yii;
use yii\base\Model;

class CommonFormAction extends AbstractAction
{
    /**
     * @var string
     */
    public $doMethod;

    public function run()
    {
        /** @var Model $model */
        $model = Yii::createObject($this->modelClass);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $result = $this->doMethodOrCallback($this->doMethod, $model);
            $this->messageAlert($result, $model);
        }

        return $this->controller->render($this->controller->id, [
            'model' => $model
        ]);
    }
}
