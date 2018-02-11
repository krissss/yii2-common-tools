<?php

namespace kriss\actions\web\crud;

use yii\base\Model;

class CreateAction extends AbstractAction
{
    /**
     * @var string|callable
     */
    public $saveMethod = 'save';
    /**
     * @var bool
     */
    public $ajax = true;
    /**
     * @var string
     */
    public $view = '_create_update';
    /**
     * @var string
     */
    public $operateMsg = '新增';
    /**
     * @var callable
     */
    public $beforeValidateCallback;

    public function run()
    {
        /** @var Model $model */
        $model = new $this->modelClass();

        return $this->createOrUpdate($model, $this->ajax, $this->saveMethod, $this->view, $this->operateMsg, $this->beforeValidateCallback);
    }

}