<?php

namespace kriss\behaviors\web;

use Yii;
use yii\base\ActionFilter;

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

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->getResponse()->redirect($this->loginUrl);
            return false;
        }
        return parent::beforeAction($action);
    }
}
