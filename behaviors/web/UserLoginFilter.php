<?php

namespace kriss\behaviors\web;

use Yii;
use yii\base\ActionFilter;

class UserLoginFilter extends ActionFilter
{
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