<?php

namespace kriss\actions\rest\crud;

use Yii;
use yii\web\HttpException;

class ModelOperateAction extends AbstractAction
{
    /**
     * @var string
     */
    public $idAttribute = 'id';
    /**
     * callback($model)
     * @var string|callable
     */
    public $doMethod;

    public function run()
    {
        $id = Yii::$app->request->post($this->idAttribute);
        if (!$id) {
            throw new HttpException(422, 'id 必须');
        }

        $model = $this->findModel($id);
        $result = $this->doMethodOrCallback($this->doMethod, $model, $model);
        if ($result) {
            return $result;
        }
        return $model;
    }
}
