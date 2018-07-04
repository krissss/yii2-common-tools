<?php

namespace kriss\actions\web\crud;

use kriss\components\MessageAlert;
use kriss\tools\Fun;
use kriss\traits\WebControllerTrait;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\NotFoundHttpException;

abstract class AbstractAction extends Action
{
    use WebControllerTrait;

    /**
     * @var string|array
     */
    public $modelClass;
    /**
     * @var string
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @var string
     */
    public $operateMsg = '操作';
    /**
     * @var callable
     */
    public $beforeRunCallback;
    /**
     * @var callable
     */
    public $findModel;

    public function runWithParams($params)
    {
        $args = $this->controller->bindActionParams($this, $params);
        if ($this->beforeRunCallback) {
            if (is_callable($this->beforeRunCallback)) {
                call_user_func_array($this->beforeRunCallback, $args);
            } elseif (is_string($this->beforeRunCallback)) {
                call_user_func_array([$this->controller, $this->beforeRunCallback], $args);
            }
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
            $result = call_user_func($method, ...$parameter);
        } else {
            throw new InvalidConfigException('$method 定义错误');
        }
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if ($this->findModel) {
            return call_user_func($this->findModel, $id);
        }

        $model = call_user_func([$this->modelClass, 'findOne'], $id);
        if (!$model) {
            throw new NotFoundHttpException('No Record');
        }
        return $model;
    }

    /**
     * @param $result
     * @param $model
     */
    protected function messageAlert($result, $model)
    {
        if ($result) {
            MessageAlert::success($this->operateMsg . '成功');
        } else {
            MessageAlert::error($this->operateMsg . '失败：' . Fun::formatModelErrors2String($model->errors));
        }
    }

    /**
     * 跳转到前一个页面
     */
    protected function redirectPrevious()
    {
        return $this->actionPreviousRedirect($this->controller);
    }
}
