<?php

namespace kriss\modules\auth\tools;

use yii\helpers\Html;

class AuthHtml extends Html
{
    /**
     * @inheritdoc
     */
    public static function a($text, $url = null, $options = [])
    {
        if (AuthValidate::checkRoute($url)) {
            return parent::a($text, $url, $options);
        }
        return '';
    }
}
