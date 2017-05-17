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

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        foreach ($this->behaviors as $key => $behavior) {
            $behaviors[$key] = $behavior;
        }

        return $behaviors;
    }
}
