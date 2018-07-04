<?php

namespace kriss\actions\web\crud;

class CreateAction extends AbstractCUAction
{
    /**
     * @var string
     */
    public $operateMsg = '新增';

    public function run()
    {
        $model = $this->newModel();

        return $this->createOrUpdate($model);
    }
}
