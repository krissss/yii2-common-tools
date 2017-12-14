<?php

namespace kriss\modules\auth\models;

use yii\base\Component;

class Auth extends Component
{
    const PERMISSION_ID = 10;
    const ROLE_ID = 20;

    const PERMISSION = 'permission';
    const PERMISSION_VIEW = 'permissionView';

    const ROLE = 'role';
    const ROLE_VIEW = 'roleView';
    const ROLE_CREATE = 'roleCreate';
    const ROLE_UPDATE = 'roleUpdate';
    const ROLE_DELETE = 'roleDelete';

    /**
     * @return array
     */
    public static function getMessageData()
    {
        return [
            static::PERMISSION => '权限管理',
            static::PERMISSION_VIEW => '查看权限',

            static::ROLE => '角色管理',
            static::ROLE_VIEW => '查看角色',
            static::ROLE_CREATE => '新增角色',
            static::ROLE_UPDATE => '修改角色',
            static::ROLE_DELETE => '删除角色',
        ];
    }

    /**
     * @return array
     */
    public static function initData()
    {
        return [
            [
                'id' => static::PERMISSION_ID, 'name' => static::PERMISSION,
                'children' => [
                    ['id' => static::PERMISSION_ID . 1, 'name' => static::PERMISSION_VIEW],
                ],
            ], [
                'id' => static::ROLE_ID, 'name' => static::ROLE,
                'children' => [
                    ['id' => static::ROLE_ID . 1, 'name' => static::ROLE_VIEW],
                    ['id' => static::ROLE_ID . 2, 'name' => static::ROLE_CREATE],
                    ['id' => static::ROLE_ID . 3, 'name' => static::ROLE_UPDATE],
                    ['id' => static::ROLE_ID . 4, 'name' => static::ROLE_DELETE],
                ],
            ],
        ];
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function getName($key){
        $messages = static::getMessageData();
        return isset($messages[$key]) ? $messages[$key] : $key;
    }
}