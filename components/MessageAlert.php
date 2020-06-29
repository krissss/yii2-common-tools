<?php

namespace kriss\components;

use Yii;

/**
 * @method static void error(string ...$messages)
 * @method static void danger(string ...$messages)
 * @method static void success(string ...$messages)
 * @method static void info(string ...$messages)
 * @method static void warning(string ...$messages)
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
