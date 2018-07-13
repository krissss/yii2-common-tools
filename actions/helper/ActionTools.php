<?php

namespace kriss\actions\helper;

use kriss\actions\rest\crud\AbstractAction;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\UnknownPropertyException;
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

    /**
     * 获取 trait 的属性，不需要定义 trait 的属性，需要配合 rest/AbstractAction 下的 __get __set 实现
     * @see AbstractAction
     *
     * @param $trait
     * @param $property
     * @param $defaultValue
     * @return mixed
     */
    public static function getTraitProperty($trait, $property, $defaultValue)
    {
        try {
            $value = $trait->$property;
        } catch (UnknownPropertyException $e) {
            return $defaultValue;
        }

        return $value;
    }
}
