<?php

namespace kriss\modules\auth\components;

use kriss\modules\auth\tools\AuthValidate;
use yii\base\ActionFilter;
use yii\helpers\ArrayHelper;

class UserAuthFilter extends ActionFilter
{
    /**
     * 需要验证的 actions
     * key 为 action id
     * value 为 权限名，可以是 string 或者 callback，若为 string，则使用 AuthValidate::run($authString) 校验
     * eg:
     * ['index' => Auth::RECHARGE_CARD_ACTIVATION]
     * @var array
     */
    public $actions = [];

    public function beforeAction($action)
    {
        $authMethod = ArrayHelper::getValue($this->actions, $action->id);
        if (is_string($authMethod)) {
            AuthValidate::run($authMethod);
        } elseif (is_callable($authMethod)) {
            call_user_func($authMethod, $action);
        }
        return parent::beforeAction($action);
    }
}
