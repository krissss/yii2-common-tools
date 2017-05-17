<?php

namespace kriss\modules\auth;

/**
 * auth module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'kriss\modules\auth\controllers';
    /**
     * @inheritdoc
     */
    public $defaultRoute = 'permission';
    /**
     * other behaviors
     * @var array
     */
    public $behaviors = [];
    /**
     * auth operation which to be hidden
     * use id in table auth_operation
     * @var array
     */
    public $skipAuthOptions = [];

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        foreach ($this->behaviors as $key => $behavior) {
            $behaviors[$key] = $behavior;
        }

        return $behaviors;
    }
}
