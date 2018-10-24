<?php

namespace kriss\modules\auth;

use kriss\modules\auth\models\AuthOperation;
use kriss\modules\auth\models\AuthRole;
use kriss\modules\auth\models\AuthRoleSearch;
use kriss\traits\KrissTranslationTrait;

/**
 * auth module definition class
 */
class Module extends \yii\base\Module
{
    use KrissTranslationTrait;

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'kriss\modules\auth\controllers';
    /**
     * @inheritdoc
     */
    public $defaultRoute = 'permission';
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

    public function init()
    {
        $this->initKrissI18N();
        parent::init();
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
