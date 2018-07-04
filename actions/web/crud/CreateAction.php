<?php

namespace kriss\actions\web\crud;

use Yii;
use yii\base\Model;

class CreateAction extends AbstractCUAction
{
    /**
     * @var string
     */
    public $operateMsg = '新增';

    public function run()
    {
        /** @var Model $model */
        $model = Yii::createObject($this->modelClass);

        return $this->createOrUpdate($model);
    }
}
