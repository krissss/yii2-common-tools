<?php

namespace kriss\actions\web\crud;

use Yii;

class UpdateAction extends AbstractCUAction
{
    /**
     * @var string
     */
    public $operateMsg;

    public function init()
    {
        if (!isset($this->operateMsg)) {
            $this->operateMsg = Yii::t('kriss', '修改');
        }

        parent::init();
    }

    public function run($id)
    {
        $model = $this->findModel($id, $this->controller);

        return $this->createOrUpdate($model);
    }
}
