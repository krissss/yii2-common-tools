<?php

namespace kriss\modules\auth\models;

use Yii;

class Auth
{
    const CAN_PERMISSION_PERMISSION = true;

    const PERMISSION_ID = 10000;
    const ROLE_ID = 11000;

    const AUTH__PERMISSION = 'auth/permission';
    const AUTH__PERMISSION__INDEX = 'auth/permission/index';

    const AUTH__ROLE = 'auth/role';
    const AUTH__ROLE__CREATE = 'auth/role/create';
    const AUTH__ROLE__DELETE = 'auth/role/delete';
    const AUTH__ROLE__INDEX = 'auth/role/index';
    const AUTH__ROLE__UPDATE = 'auth/role/update';
    const AUTH__ROLE__VIEW = 'auth/role/view';

    /**
     * @return array
     */
    public static function getMessageData()
    {
        return static::CAN_PERMISSION_PERMISSION ? [
            static::AUTH__PERMISSION => '权限管理',
            static::AUTH__PERMISSION__INDEX => '列表',

            static::AUTH__ROLE => '角色管理',
            static::AUTH__ROLE__CREATE => '新增',
            static::AUTH__ROLE__DELETE => '删除',
            static::AUTH__ROLE__INDEX => '列表',
            static::AUTH__ROLE__UPDATE => '修改',
            static::AUTH__ROLE__VIEW => '详情',
        ] : [];
    }

    /**
     * @return array
     */
    public static function initData()
    {
        return static::CAN_PERMISSION_PERMISSION ? [
            [
                'id' => static::PERMISSION_ID, 'name' => static::AUTH__PERMISSION,
                'children' => [
                    ['id' => static::PERMISSION_ID + 1, 'name' => static::AUTH__PERMISSION__INDEX],
                ],
            ],
            [
                'id' => static::ROLE_ID, 'name' => static::AUTH__ROLE,
                'children' => [
                    ['id' => static::ROLE_ID + 1, 'name' => static::AUTH__ROLE__INDEX],
                    ['id' => static::ROLE_ID + 2, 'name' => static::AUTH__ROLE__VIEW],
                    ['id' => static::ROLE_ID + 3, 'name' => static::AUTH__ROLE__CREATE],
                    ['id' => static::ROLE_ID + 4, 'name' => static::AUTH__ROLE__UPDATE],
                    ['id' => static::ROLE_ID + 5, 'name' => static::AUTH__ROLE__DELETE],
                ],
            ],
        ] : [];
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function getName($key)
    {
        $messages = static::getMessageData();
        $msg = isset($messages[$key]) ? $messages[$key] : $key;
        return Yii::t('app', $msg);
    }
}
