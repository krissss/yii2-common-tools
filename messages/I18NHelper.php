<?php

namespace kriss\messages;

use Yii;

class I18NHelper
{
    /**
     * framework entry index.php
     *
     * $app = (new yii\web\Application($config));
     * I18NHelper::registerKrissMessage();
     * $app->run();
     */
    public static function registerKrissMessage()
    {
        $translations = Yii::$app->i18n->translations;
        if (!isset($translations['kriss'])) {
            Yii::$app->i18n->translations['kriss'] = static::getKrissMessageConfig();
        }
    }

    /**
     * i18n component
     *
     * 'components' => [
     *      // ...
     * '    'i18n' => [
     *         'translations' => [
     *             'kriss' => I18NHelper::getKrissMessageConfig()
     *          ],
     *      ],
     * ],
     *
     * @return array
     */
    public static function getKrissMessageConfig()
    {
        return [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => __DIR__,
            'forceTranslation' => true,
        ];
    }
}
