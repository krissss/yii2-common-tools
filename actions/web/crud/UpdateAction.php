<?php

namespace kriss\actions\web\crud;

class UpdateAction extends AbstractCUAction
{
    /**
     * @var string
     */
    public $operateMsg = '修改';

    public function run($id)
    {
        $model = $this->findModel($id, $this->controller);

        return $this->createOrUpdate($model);
    }
}
