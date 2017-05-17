<?php

namespace kriss\traits;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;

trait WebControllerTrait
{
    /**
     * @var Controller
     */
    public $thisController;

    /**
     * remember current url for redirect
     */
    public function rememberUrl()
    {
        Yii::trace('remember current url', __METHOD__);
        Url::remember();
    }

    /**
     * redirect to rememberUrl() remembered url or return the redirect url string
     * if request is ajax, return the redirect url string
     * otherwise, send redirect to rememberUrl() remembered
     * @return string|\yii\web\Response
     */
    public function actionPreviousRedirect()
    {
        if (Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            Yii::trace('return previous url string', __METHOD__);
            return Url::previous();
        } else {
            Yii::trace('redirect previous url', __METHOD__);
            return $this->thisController->redirect(Url::previous());
        }
    }
}