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
     * @var int
     */
    public $superAdminId = 1;
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

    public function actionIndex()
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

    protected function getInitRoles()
    {
        return [
            $this->superAdminId => ['超级管理员', '拥有所有权限', 'all']
        ];
    }

    protected function initAuthOperations()
    {
        $authClass = $this->authClass;
        $permissions = $authClass::initData();
        echo "initAuthOperations ______________ start\n";
        AuthOperation::deleteAll();
        foreach ($permissions as $permission) {
            $model = new AuthOperation();
            $model->id = $permission['id'];
            $model->parent_id = 0;
            $model->name = $permission['name'];
            $model->save(false);
            foreach ($permission['children'] as $item) {
                $model = new AuthOperation();
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
        AuthRole::deleteAll();
        $roles = $this->getInitRoles();
        foreach ($roles as $id => $role) {
            $model = new AuthRole();
            $model->id = $id;
            $model->name = $role[0];
            $model->description = $role[1];
            $model->operation_list = $role[2];
            $model->save(false);
        }

        if ($this->superAdminId) {
            $adminClass = $this->adminClass;
            $superAdmin = $adminClass::findOne($this->superAdminId); // 确保该用户为超级管理员
            $authRoleAttribute = $this->authRoleAttribute;
            $superAdmin->$authRoleAttribute = '1';
            $superAdmin->save(false);
        }

        echo "initAuthRole ______________ ok\n";
    }
}