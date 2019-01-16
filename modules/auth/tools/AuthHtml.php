<?php

namespace kriss\modules\auth\tools;

use yii\helpers\ArrayHelper;
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

    /**
     * @param $name
     * @param $authAttribute
     * @param string $content
     * @param array $options
     * @return string
     */
    public static function authTag($name, $authAttribute, $content = '', $options = []) {
        $authAttributeValue = ArrayHelper::getValue($options, $authAttribute);
        if ($authAttributeValue && AuthValidate::checkRoute($authAttributeValue)) {
            return parent::tag($name, $content, $options);
        }
        return '';
    }
}
