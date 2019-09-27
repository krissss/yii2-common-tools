<?php

namespace kriss\behaviors\web;

use Yii;
use yii\base\ActionFilter;
use yii\helpers\Url;

/**
 * 用户登录过滤
 */
class UserLoginFilter extends ActionFilter
{
    /**
     * 未登录时的跳转地址
     * @var array
     */
    public $loginUrl = ['/site/login'];
    /**
     * @var bool
     */
    public $rememberUrl = true;
    /**
     * @var string
     */
    public $rememberUrlName = null;

    public function beforeAction($action)
    {
        if ($this->rememberUrl && Yii::$app->request->isGet) {
            Url::remember('', $this->rememberUrlName);
        }
        if (Yii::$app->user->isGuest) {
            Yii::$app->getResponse()->redirect($this->loginUrl);
            return false;
        }
        return parent::beforeAction($action);
    }
}
