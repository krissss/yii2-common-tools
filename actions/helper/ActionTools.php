<?php

namespace kriss\actions\helper;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ActionTools
{
    /**
     * 调用 $class 的 $method 方法
     * @param $method string|callable
     * @param $class object|string
     * @param array $parameter
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function invokeClassMethod($class, $method, ...$parameter)
    {
        if (is_string($method)) {
            $result = call_user_func([$class, $method], ...$parameter);
        } elseif (is_callable($method)) {
            $parameter[] = $class;
            $result = call_user_func($method, ...$parameter);
        } else {
            throw new InvalidConfigException("method: {$method} 定义错误");
        }
        return $result;
    }

    /**
     * @param $config array
     * @param $defaultClass string
     * @return array
     */
    public static function mergeDefaultClass(&$config, $defaultClass)
    {
        if (is_array($config) && !isset($config['class'])) {
            $config['class'] = $defaultClass;
        }
        return $config;
    }

    /**
     * @param $class
     * @param $params
     */
    public static function generateYiiObjectConfig(&$class, $params)
    {
        if (is_string($class)) {
            $class = ['class' => $class];
        }
        if (is_array($class)) {
            $class = ArrayHelper::merge($class, $params);
        }
    }

    /**
     * @param $msg
     * @return Model
     */
    public static function restValidateError($msg)
    {
        $model = new Model();
        $model->addError('xxx', $msg);
        return $model;
    }
}
