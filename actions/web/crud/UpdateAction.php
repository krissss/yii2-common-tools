<?php

namespace kriss\actions\web\crud;

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
        $model = $this->findModel($id);

        return $this->createOrUpdate($model);
    }
}
