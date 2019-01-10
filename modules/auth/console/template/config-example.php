<?php

return [
    'auth/permission' => [
        'name' => '权限',
        'items' => [
            'index' => [
                'name' => '列表',
                'is_delete' => false,
            ],
        ],
    ],
    'auth/role' => [
        'name' => '角色',
        'items' => [
            'create' => [
                'name' => '新增',
                'is_delete' => false,
            ],
            'delete' => [
                'name' => '删除',
                'is_delete' => false,
            ],
            'index' => [
                'name' => '列表',
                'is_delete' => false,
            ],
            'update' => [
                'name' => '修改',
                'is_delete' => false,
            ],
            'view' => [
                'name' => '查看',
                'is_delete' => false,
            ],
        ],
    ],
];
