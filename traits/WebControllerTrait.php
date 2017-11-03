<?php

namespace kriss\traits;

use kriss\tools\Fun;
use Yii;
use yii\base\Widget;
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

    /**
     * @param $view
     * @param array $params
     * @return mixed
     */
    public function renderAjax($view, $params = [])
    {
        // fix form validate
        Widget::$autoIdPrefix = Fun::generateRandString(random_int(4, 6));
        return parent::renderAjax($view, $params);
    }
}