<?php

namespace kriss\modules\auth;

use kriss\modules\auth\models\AuthOperation;
use kriss\modules\auth\models\AuthRole;
use kriss\modules\auth\models\AuthRoleSearch;

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
    /**
     * AuthOperation Class
     * must be subClass of kriss\modules\auth\models\AuthOperation
     * @var string|AuthOperation
     */
    public $authOperationClass = 'kriss\modules\auth\models\AuthOperation';
    /**
     * AuthRole Class
     * must be subClass of kriss\modules\auth\models\AuthRole
     * @var string|AuthRole
     */
    public $authRoleClass = 'kriss\modules\auth\models\AuthRole';
    /**
     * AuthRoleSearch Class
     * must be subClass of kriss\modules\auth\models\AuthRoleSearch
     * @var string|AuthRoleSearch
     */
    public $authRoleSearchClass = 'kriss\modules\auth\models\AuthRoleSearch';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // 检查 authOperationClass 和 authRoleClass
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        foreach ($this->behaviors as $key => $behavior) {
            $behaviors[$key] = $behavior;
        }

        return $behaviors;
    }

    /**
     * @return AuthOperation|string
     */
    public static function getAuthOperationClass()
    {
        return Module::getInstance()->authOperationClass;
    }

    /**
     * @return AuthRole|string
     */
    public static function getAuthRoleClass()
    {
        return Module::getInstance()->authRoleClass;
    }

    /**
     * @return AuthRoleSearch|string
     */
    public static function getAuthRoleSearchClass()
    {
        return Module::getInstance()->authRoleSearchClass;
    }
}
