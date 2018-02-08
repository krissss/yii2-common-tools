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
     * @param false|\yii\web\Controller $controller
     */
    public function rememberUrl($controller = false)
    {
        $controller = $controller ?: $this;
        Yii::trace('remember current url', __METHOD__);
        /** @var $this Controller */
        Url::remember('', get_class($controller));
    }

    /**
     * redirect to rememberUrl() remembered url or return the redirect url string
     * if request is ajax, return the redirect url string
     * otherwise, send redirect to rememberUrl() remembered
     * @param false|\yii\web\Controller $controller
     * @return string|\yii\web\Response
     */
    public function actionPreviousRedirect($controller = false)
    {
        /** @var Controller $this */
        $controller = $controller ?: $this;
        $previous = Url::previous(get_class($controller));
        if (Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            Yii::trace('return previous url string: ' . $previous, __METHOD__);
            return $previous;
        } else {
            Yii::trace('redirect previous url: ' . $previous, __METHOD__);
            return $controller->redirect($previous);
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