<?php

namespace kriss\components;

use Yii;

/**
 * @method success(string | array $messages)
 * @method error(string | array $messages)
 * @method warning(string | array $messages)
 * @method info(string | array $messages)
 */
class MessageAlert
{
    /**
     * @param $messages array key 是 error,success,info;value 可以是字符串或者数组
     */
    public static function set($messages)
    {
        foreach ($messages as $type => $message) {
            Yii::$app->session->setFlash($type, $message);
        }
    }

    /**
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($name, $arguments)
    {
        static::set([$name => $arguments]);
    }
}