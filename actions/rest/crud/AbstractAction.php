<?php

namespace kriss\actions\rest\crud;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

abstract class AbstractAction extends Action
{
    /**
     * 在使用 array 配置时如果要用到 比如： Yii::$app->user->id 时，请使用 callable，
     * 否则 Yii::$app->user->id 为空
     * @var string|array|callable
     */
    public $modelClass;
    /**
     * @var string
     */
    public $scenario = null;
    /**
     * @var bool
     */
    public $loadDefaultValue = true;
    /**
     * @var string|callable
     */
    public $beforeRunCallback;
    /**
     * @var string|callable
     */
    public $findModel;

    public function runWithParams($params)
    {
        $args = $this->controller->bindActionParams($this, $params);
        if ($this->beforeRunCallback) {
            if ($this->beforeRunCallback) {
                $this->doMethodOrCallback($this->beforeRunCallback, $this->controller, ...$args);
            }
        }
        return parent::runWithParams($params);
    }

    /**
     * @param $method string|callable
     * @param $class object|string
     * @param array $parameter
     * @return bool
     * @throws InvalidConfigException
     */
    protected function doMethodOrCallback($method, $class, ...$parameter)
    {
        if (is_string($method)) {
            $result = call_user_func([$class, $method], ...$parameter);
        } elseif (is_callable($method)) {
            $result = call_user_func($method, ...$parameter);
        } else {
            throw new InvalidConfigException("method: {$method} 定义错误");
        }
        return $result;
    }

    /**
     * @return Model|ActiveRecord
     * @throws InvalidConfigException
     */
    protected function newModel()
    {
        /** @var Model $model */
        $model = Yii::createObject($this->modelClass);
        $this->scenario && $model->setScenario($this->scenario);
        if ($this->loadDefaultValue && $model instanceof ActiveRecord) {
            $model->loadDefaultValues();
        }
        return $model;
    }

    /**
     * @param $id
     * @param bool $throwException
     * @return Model|ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($id, $throwException = false)
    {
        if ($this->findModel) {
            if (is_string($this->findModel)) {
                return call_user_func([$this->controller, $this->findModel], $id);
            } elseif (is_callable($this->findModel)) {
                return call_user_func($this->findModel, $id);
            }
        }

        $model = call_user_func([$this->modelClass, 'findOne'], $id);
        if (!$model) {
            if (!$throwException) {
                return $this->validateError('No Record');
            } else {
                throw new NotFoundHttpException('No Record');
            }
        }
        if ($this->loadDefaultValue) {
            $model->loadDefaultValues();
        }
        return $model;
    }

    /**
     * @param $msg
     * @return Model
     */
    protected function validateError($msg)
    {
        $model = new Model();
        $model->addError('xxx', $msg);
        return $model;
    }
}
