<?php

namespace kriss\components;

use Yii;

class MessageAlert
{
    /**
     * @param $messages array key 是 error,success,info;value 可以是字符串或者数组
     */
    public static function set($messages){
        foreach ($messages as $type => $message){
            Yii::$app->session->setFlash($type, $message);
        }
    }
}