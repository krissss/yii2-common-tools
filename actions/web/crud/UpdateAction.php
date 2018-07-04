<?php

namespace kriss\actions\web\crud;

use yii\db\ActiveRecord;

class UpdateAction extends AbstractCUAction
{
    /**
     * @var string
     */
    public $operateMsg = '修改';
    /**
     * @var string|callable
     */
    public $findModel;

    public function run($id)
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id);

        return $this->createOrUpdate($model);
    }
}
