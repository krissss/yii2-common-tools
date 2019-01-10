<?php

namespace kriss\modules\auth\console\controllers;

use kriss\components\ActiveRecord;
use kriss\modules\auth\models\Auth;
use kriss\modules\auth\models\AuthOperation;
use kriss\modules\auth\models\AuthRole;
use Yii;
use yii\base\Exception;
use yii\console\Controller;

class InitAuthController extends Controller
{
    /**
     * admin class name
     * @var ActiveRecord
     */
    public $adminClass = 'common\models\Admin';
    /**
     * super admin id
     * can be null or ''
     * @var int
     */
    public $superAdminId = 1;
    /**
     * @var int
     */
    public $superAdminRoleId = 1;
    /**
     * admin auth_role attribute
     * @var string
     */
    public $authRoleAttribute = 'auth_role';
    /**
     * subclass of kriss\modules\auth\models\Auth
     * @var Auth
     */
    public $authClass = 'common\models\base\Auth';
    /**
     * subclass of kriss\modules\auth\models\AuthRole
     * @var AuthRole
     */
    public $authRoleClass = 'kriss\modules\auth\models\AuthRole';
    /**
     * subclass of kriss\modules\auth\models\AuthOperation
     * @var AuthOperation
     */
    public $authOperationClass = 'kriss\modules\auth\models\AuthOperation';
    /**
     * [
     *   [role, desc, [permission], roleId (optional)]
     * ]
     * @var array
     */
    public $initRoles = [
        ['超级管理员', '拥有所有权限', ['all'], 1],
    ];

    /**
     * delete and create operations and role
     * @throws Exception
     */
    public function actionRestore()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->initAuthOperations();
            $this->initAuthRole();
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    /**
     * delete and create operations
     * @throws Exception
     */
    public function actionUpdateOperations()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->initAuthOperations();
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    protected function initAuthOperations()
    {
        $authClass = $this->authClass;
        $permissions = $authClass::initData();
        echo "initAuthOperations ______________ start\n";
        $authOperationClass = $this->authOperationClass;
        $authOperationClass::deleteAll();
        foreach ($permissions as $permission) {
            /** @var AuthOperation $model */
            $model = new $authOperationClass();
            $model->id = $permission['id'];
            $model->parent_id = 0;
            $model->name = $permission['name'];
            $model->save(false);
            foreach ($permission['children'] as $item) {
                /** @var AuthOperation $model */
                $model = new $authOperationClass();
                $model->id = $item['id'];
                $model->parent_id = $permission['id'];
                $model->name = $item['name'];
                $model->save(false);
            }
        }
        echo "initAuthOperations ______________ ok\n";
    }

    protected function initAuthRole()
    {
        echo "initAuthRole ______________ start\n";
        $authRoleClass = $this->authRoleClass;
        $authRoleClass::deleteAll();
        $roles = $this->initRoles;
        if ($roles) {
            foreach ($roles as $role) {
                /** @var AuthRole $model */
                $model = new $authRoleClass();
                if (isset($role[3])) {
                    $model->id = isset($role[3]);
                }
                $model->name = $role[0];
                $model->description = $role[1];
                $model->operation_list = implode(';', $role[2]);
                $model->save(false);
            }
        }

        if ($this->superAdminId) {
            $adminClass = $this->adminClass;
            $superAdmin = $adminClass::findOne($this->superAdminId); // 确保该用户为超级管理员
            $authRoleAttribute = $this->authRoleAttribute;
            $superAdmin->$authRoleAttribute = $this->superAdminRoleId;
            $superAdmin->save(false);
        }

        echo "initAuthRole ______________ ok\n";
    }
}
