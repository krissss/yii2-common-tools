<?php

namespace kriss\actions\rest\crud;

use Yii;

class CommonFormAction extends AbstractAction
{
    /**
     * @var string
     */
    public $doMethod;

    public function run()
    {
        $model = $this->newModel();

        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            $result = $this->doMethodOrCallback($this->doMethod, $model);
            if ($result !== false) {
                return $result;
            }
        }

        return $model;
    }
}
