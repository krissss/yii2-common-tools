<?php

namespace kriss\actions\web\crud;

use Yii;

class CreateAction extends AbstractCUAction
{
    /**
     * @var string
     */
    public $operateMsg;

    public function init()
    {
        parent::init();
        if (!isset($this->operateMsg)) {
            $this->operateMsg = Yii::t('kriss', '新增');
        }
    }

    public function run()
    {
        $model = $this->newModel();

        return $this->createOrUpdate($model);
    }
}
