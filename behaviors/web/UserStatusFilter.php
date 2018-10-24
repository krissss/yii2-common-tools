<?php

namespace kriss\behaviors\web;

use kriss\traits\KrissTranslationTrait;
use Yii;
use yii\base\ActionFilter;

/**
 * 用户状态过滤
 */
class UserStatusFilter extends ActionFilter
{
    use KrissTranslationTrait;

    /**
     * 用户状态字段
     * @var string
     */
    public $statusParam = 'status';
    /**
     * 不允许访问的状态
     * @var array
     */
    public $notAllowedStatus = [];
    /**
     * 拒绝时的错误信息
     * @var string
     */
    public $errorMessage;
    /**
     * 拒绝时跳转的界面
     * @var array
     */
    public $redirectUrl = ['/site/login'];

    public function init()
    {
        $this->initKrissI18N();
        if (!isset($this->errorMessage)) {
            $this->errorMessage = Yii::t('kriss', '用户状态不允许访问');
        }

        parent::init();
    }

    public function beforeAction($action)
    {
        $statusParam = $this->statusParam;
        $user = Yii::$app->user->getIdentity();
        if (!$user || in_array($user->$statusParam, $this->notAllowedStatus)) {
            Yii::$app->session->setFlash('error', $this->errorMessage);
            Yii::$app->getResponse()->redirect($this->redirectUrl);
            return false;
        }
        return parent::beforeAction($action);
    }
}
