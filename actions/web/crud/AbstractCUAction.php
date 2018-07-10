<?php

namespace kriss\actions\web\crud;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;

abstract class AbstractCUAction extends AbstractAction
{
    /**
     * @var bool
     */
    public $isAjax = true;
    /**
     * @var string
     */
    public $doMethod = 'save';
    /**
     * @var string
     */
    public $view = '_create_update';
    /**
     * @var callable
     */
    public $beforeValidateCallback;
    /**
     * @var callable
     */
    public $beforeRenderCallback;

    /**
     * 新增或修改操作
     * @param $model ActiveRecord|Model
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function createOrUpdate($model)
    {
        if ($model->load(Yii::$app->request->post())) {

            $this->beforeValidateCallback && call_user_func($this->beforeValidateCallback, $model);

            ($this->doMethod === 'save' || $model->validate()) && $this->doMethodOrCallback($this->doMethod, $model, $model);

            return $this->redirectPrevious();
        }

        $this->beforeRenderCallback && call_user_func($this->beforeRenderCallback, $model);

        $renderMethod = $this->isAjax ? 'renderAjax' : 'render';
        return $this->controller->$renderMethod($this->view, [
            'model' => $model
        ]);
    }
}
