<?php

namespace kriss\modules\auth\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%auth_role}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $operation_list
 */
class AuthRole extends \yii\db\ActiveRecord
{
    public $_operations = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 255],
            [['operation_list'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '角色名称',
            'description' => '描述',
            'operation_list' => '权限列表',
        ];
    }

    /**
     * @param $ids
     * @return string
     */
    public static function findName($ids)
    {
        $models = static::find()->select('name')->andWhere(['id' => $ids])->asArray()->all();
        $nameArr = ArrayHelper::getColumn($models, 'name');
        return implode(',', $nameArr);
    }

    /**
     * 检查登录的用户是否可以修改修改角色
     * 超级管理员和用户自己不能修改
     * @param $roleId
     * @return bool
     */
    public static function canLoginUserModify($roleId)
    {
        /** @var \kriss\modules\auth\components\User $user */
        $user = Yii::$app->user;
        // 超级管理员角色不能被修改
        if ($roleId == $user->superAdminId) {
            return false;
        }
        // 角色 id 在当前登录的用户的角色内，不能修改
        $userIdentity = $user->identity;
        $authRole = $user->userAuthRoleAttribute;
        $adminAuthRole = $userIdentity->$authRole;
        if (strpos(",$adminAuthRole,", ",$roleId,") !== false) {
            return false;
        }
        return true;
    }

    /**
     * 查询所有的 id 和 name
     * @param bool $map
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findAllIdName($map = false)
    {
        $models = static::find()->select(['id', 'name'])->asArray()->all();
        if ($map) {
            $models = ArrayHelper::map($models, 'id', 'name');
        }
        return $models;
    }

    /**
     * 根据用户角色 id 获取所有的操作权限
     * 返回值如下：
     * [
     *    'roleView', 'roleCreate'
     * ]
     * @param $authRoleIds array
     * @return array
     */
    public static function getOperationsArr($authRoleIds)
    {
        $authRoleIds = array_filter($authRoleIds);
        if (!$authRoleIds) {
            return [];
        }
        return ArrayHelper::getColumn(static::find()->select('operation_list')->andWhere(['id' => $authRoleIds])->asArray()->all(), 'operation_list');
    }
}
