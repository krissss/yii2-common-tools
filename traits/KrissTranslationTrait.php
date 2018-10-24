<?php

namespace kriss\traits;

use Yii;

trait KrissTranslationTrait
{
    public function initKrissI18N()
    {
        $translations = Yii::$app->i18n->translations;
        if (!isset($translations['kriss'])) {
            Yii::setAlias('@krissMessage', __DIR__ . '/../messages');
            Yii::$app->i18n->translations['kriss'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@krissMessage',
                'forceTranslation' => true,
            ];
        }
    }
}
