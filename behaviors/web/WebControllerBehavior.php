<?php

namespace kriss\behaviors\web;

use kriss\traits\WebControllerTrait;
use yii\base\Behavior;
use Yii;
use yii\helpers\Url;

class WebControllerBehavior extends Behavior
{
    use WebControllerTrait;

    /**
     * doc in WebControllerTrait
     */
    public function actionPreviousRedirect()
    {
        if (Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            Yii::trace('return previous url string', __METHOD__);
            return Url::previous();
        } else {
            Yii::trace('redirect previous url', __METHOD__);
            return $this->controller->redirect(Url::previous());
        }
    }
}