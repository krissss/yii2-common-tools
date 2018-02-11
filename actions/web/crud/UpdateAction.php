<?php

namespace kriss\actions\web\crud;

use yii\db\ActiveRecord;

class UpdateAction extends AbstractAction
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
    public $operateMsg = '修改';
    /**
     * @var callable
     */
    public $beforeValidateCallback;

    public function run($id)
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id);

        return $this->createOrUpdate($model, $this->ajax, $this->saveMethod, $this->view, $this->operateMsg, $this->beforeValidateCallback);
    }

}