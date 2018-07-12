<?php

namespace kriss\actions\rest\crud;

use kriss\actions\helper\ActionTools;
use kriss\actions\traits\AutoSetUserTrait;
use kriss\actions\traits\ModelClassActionTrait;
use Yii;

class CommonFormAction extends AbstractAction
{
    use AutoSetUserTrait;
    use ModelClassActionTrait;

    /**
     * @var string
     */
    public $doMethod;

    public function run()
    {
        $this->autoMergeUserId($this->modelClass);
        $model = $this->newModel();

        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            $result = ActionTools::invokeClassMethod($model, $this->doMethod);
            if ($result !== false) {
                return $result;
            }
        }

        return $model;
    }
}
