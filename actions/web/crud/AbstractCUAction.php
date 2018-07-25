<?php

namespace kriss\actions\web\crud;

use yii\base\Model;
use yii\db\ActiveRecord;

abstract class AbstractCUAction extends AbstractModelAction
{
    /**
     * @var string
     */
    public $doMethod = 'save';
    /**
     * @var bool
     */
    public $isAjax = true;
    /**
     * @var string
     */
    public $view = '_create_update';

    /**
     * 新增或修改操作
     * @param $model ActiveRecord|Model
     * @return mixed
     */
    protected function createOrUpdate($model)
    {
        $this->setModel($model);
        if ($this->loadPostData()) {
            $result = $this->doModelMethod(true);
            return $this->redirectAfterDoMethod($result);
        }
        return $this->renderView();
    }
}
