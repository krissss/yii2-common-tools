<?php
namespace kriss\modules\auth\models;

use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class UpdateUserRole extends Model
{
    /**
     * @var ActiveRecord
     */
    public $userClass;
    /**
     * $userClass 表的授权字段
     * @var string
     */
    public $userClassAuthRoleAttribute = 'auth_role';
    /**
     * @var integer
     */
    public $userId;

    /**
     * use in view
     * @var array
     */
    public $userRole;
    /**
     * use in view
     * @var array
     */
    public $roles;

    /**
     * @var ActiveRecord
     */
    private $user;

    public function init() {
        if (!$this->userId) {
            throw new InvalidConfigException('userId must be set');
        }
        if(!$this->userClass){
            throw new InvalidConfigException('userClass must be set');
        }
        $userClass = $this->userClass;
        $this->user = $userClass::find()->andWhere(['id' => $this->userId])->one();
        if (!$this->user) {
            throw new Exception('用户不存在');
        }
    }

    public function initData() {
        $authRoleParam = $this->userClassAuthRoleAttribute;
        $this->userRole = explode(',', $this->user->$authRoleParam);
        if (!$this->userRole) {
            $this->userRole = [];
        }

        $this->roles = ArrayHelper::map(AuthRole::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name');
    }

    public function rules() {
        return [
            ['userRole', 'safe']
        ];
    }

    public function updateUserRole() {
        $authRoleParam = $this->userClassAuthRoleAttribute;
        $this->user->$authRoleParam = $this->userRole?implode(',',$this->userRole):'';
        $this->user->save(false);
        return ['type'=>'success','msg'=>'用户角色修改成功'];
    }

}