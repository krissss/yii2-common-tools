<?php

namespace kriss\actions\rest\crud;

use kriss\actions\traits\ModelClassActionTrait;
use Yii;

class ModelOperateAction extends AbstractAction
{
    use ModelClassActionTrait;

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
            return $this->restValidateError('id 必须');
        }

        $model = $this->findModel($id, $this->controller);
        $result = $this->invokeClassMethod($model, $this->doMethod);
        if ($result !== false) {
            return $result;
        }
        return $model;
    }
}
