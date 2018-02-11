<?php

namespace kriss\actions\web\crud;

use kriss\components\MessageAlert;
use kriss\tools\Fun;
use kriss\traits\WebControllerTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\rest\Action;

abstract class AbstractAction extends Action
{
    use WebControllerTrait;

    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @var callable
     */
    public $beforeRunCallback;

    public function runWithParams($params)
    {
        $args = $this->controller->bindActionParams($this, $params);
        if($this->beforeRunCallback && is_callable($this->beforeRunCallback)) {
            call_user_func_array([$this, $this->beforeRunCallback], $args);
        }
        return parent::runWithParams($params);
    }

    /**
     * @param $method string|callable
     * @param $class Model
     * @param array $parameter
     * @return bool
     * @throws InvalidConfigException
     */
    protected function doMethodOrCallback($method, $class, ...$parameter)
    {
        if (is_string($method) && $class->hasMethod($method)) {
            $result = $class->$method(...$parameter);
        } elseif (is_callable($method)) {
            $result = call_user_func($method, $class, ...$parameter);
        } else {
            throw new InvalidConfigException('$method 定义错误');
        }
        return $result;
    }

    /**
     * 新增或修改操作
     * @param $model Model
     * @param $ajax
     * @param $saveMethod
     * @param $view
     * @param $operateMsg
     * @param $beforeValidateCallback
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function createOrUpdate($model, $ajax, $saveMethod, $view, $operateMsg, $beforeValidateCallback)
    {
        if ($model->load(Yii::$app->request->post())) {
            if ($beforeValidateCallback) {
                call_user_func($beforeValidateCallback, $model);
            }
            if ($ajax) {
                if (
                    ($saveMethod === 'save' || $model->validate())
                    && $this->doMethodOrCallback($saveMethod, $model)
                ) {
                    MessageAlert::set(['success' => $operateMsg . '成功']);
                } else {
                    if ($ajax) {
                        MessageAlert::set(['error' => $operateMsg . '失败：' . Fun::formatModelErrors2String($model->errors)]);
                    }
                }
                if ($ajax) {
                    return $this->actionPreviousRedirect($this->controller);
                }
            }
        }

        $renderMethod = $ajax ? 'renderAjax' : 'render';
        return $this->controller->$renderMethod($view, [
            'model' => $model
        ]);
    }
}