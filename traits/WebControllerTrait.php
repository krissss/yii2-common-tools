<?php

namespace kriss\traits;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * use this trait, the class must extend yii\web\Controller
 */
trait WebControllerTrait
{
    /**
     * remember current url for redirect
     */
    public function rememberUrl()
    {
        Yii::trace('remember current url', __METHOD__);
        /** @var $this Controller */
        Url::remember('', get_class($this));
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
            Yii::trace('return previous url string: ' . Url::previous(get_class($this)), __METHOD__);
            return Url::previous(get_class($this));
        } else {
            Yii::trace('redirect previous url: ' . Url::previous(get_class($this)), __METHOD__);
            /** @var $this Controller */
            return $this->redirect(Url::previous(get_class($this)));
        }
    }
}