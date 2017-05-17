<?php
/**
 * 复写权限验证的user
 * 来自于 https://github.com/funson86/yii2-auth
 * 配合权限使用
 * 在 config 中添加
 * 'components' => [
 *    'user' => [
 *       'class' => 'kriss\modules\auth\components\User',
 *       'authClass' => 'common\models\base\Auth'
 *    ],
 * ]
 */

namespace kriss\modules\auth\components;

use kriss\modules\auth\models\AuthRole;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class User extends \yii\web\User
{
    /**
     * class extends \kriss\modules\auth\models\Auth
     * @var string
     */
    public $authClass;
    /**
     * auth role attribute in Admin table
     * @var string
     */
    public $userAuthRoleAttribute = 'auth_role';

    private $_operations;

    public function init()
    {
        parent::init();

        if($this->authClass === null){
            throw new InvalidConfigException('User::authClass must be set.');
        }
    }

    /**
     * Checks if the user can perform the operation as specified by the given permission.
     *
     * if super admin, the operation role is 'all', return true all the time.
     *
     *
     * @param string $permissionName the name of the permission (e.g. "edit post") that needs access check.
     * @param array $params name-value pairs that would be passed to the rules associated
     * with the roles and permissions assigned to the user. A param with name 'user' is added to
     * this array, which holds the value of [[id]].
     * @param boolean $allowCaching whether to allow caching the result of access check.
     * When this parameter is true (default), if the access check of an operation was performed
     * before, its result will be directly returned when calling this method to check the same
     * operation. If this parameter is false, this method will always call
     * [[\yii\rbac\ManagerInterface::checkAccess()]] to obtain the up-to-date access result. Note that this
     * caching is effective only within the same request and only works when `$params = []`.
     * @return boolean whether the user can perform the operation as specified by the given permission.
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        if ($allowCaching && isset($this->_operations)) {
           $operations =  $this->_operations;
        } else {
            $user = Yii::$app->user->identity;
            $authRole = $this->userAuthRoleAttribute;
            $userAuthRoles = explode(',',$user->$authRole);
            $operationsArr = ArrayHelper::getColumn(AuthRole::find()->select('operation_list')->where(['id'=>$userAuthRoles])->asArray()->all(),'operation_list');
            $operations = implode(';',$operationsArr);
            $this->_operations = $operations;
        }

        //super admin
        if (strpos(';' . $operations . ';', 'all')){
            return true;
        }

        if (strpos(';' . $operations . ';', $permissionName) === false)
            return false;
        else
            return true;
    }
}
