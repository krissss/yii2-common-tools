<?php

namespace kriss\modules\auth\models;

use kriss\modules\auth\components\User;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;

class UpdateUserRole extends Model
{
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
     * 展示的字段
     * @var string
     */
    public $displayAttribute = 'id';

    /**
     * @var ActiveRecord
     */
    private $user;

    public function init()
    {
        if (!$this->userId) {
            throw new InvalidConfigException('userId must be set');
        }
        /** @var ActiveRecord $userClass */
        $userClass = Yii::$app->user->identityClass;
        $this->user = $userClass::find()->andWhere(['id' => $this->userId])->one();
        if (!$this->user) {
            throw new Exception('用户不存在');
        }
    }

    public function initData()
    {
        /** @var User $user */
        $user = Yii::$app->user;
        $authRoleParam = $user->userAuthRoleAttribute;
        $this->userRole = explode(',', $this->user->$authRoleParam);
        if (!$this->userRole) {
            $this->userRole = [];
        }

        $this->roles = $user->findAllRoles();
    }

    public function rules()
    {
        return [
            ['userRole', 'safe']
        ];
    }

    public function updateUserRole()
    {
        /** @var User $user */
        $user = Yii::$app->user;
        $authRoleParam = $user->userAuthRoleAttribute;
        $this->user->$authRoleParam = $this->userRole ? implode(',', $this->userRole) : '';
        $this->user->save(false);
        return ['type' => 'success', 'msg' => '用户角色修改成功'];
    }

    /**
     * 获取页面展示信息
     * @return mixed
     */
    public function getDisplayInfo()
    {
        $attribute = $this->displayAttribute;
        if ($this->user->hasAttribute($attribute)) {
            $arr['label'] = $this->user->getAttributeLabel($attribute);
            $arr['value'] = $this->user->$attribute;
            if (in_array($arr['label'], ['id', 'ID'])) {
                $arr['label'] = '用户编号';
            }
            return $arr;
        }
        $arr['label'] = '用户编号';
        $arr['value'] = $this->userId;
        return $arr;
    }
}