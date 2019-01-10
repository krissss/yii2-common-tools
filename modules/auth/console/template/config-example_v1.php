<?php

return [
    1001 => [
        'key' => 'module1',
        'name' => '模块1',
        'items' => [
            ['key' => 'view', 'name' => '查看'],
            ['key' => 'create', 'name' => '新增'],
            ['key' => 'update', 'name' => '修改', 'nameFill' => 'prepend'],
            ['key' => 'delete', 'name' => '删除模块', 'nameFill' => false],
        ],
    ],
    1002 => [
        'key' => 'module2',
        'name' => '模块2',
        'items' => [
            ['key' => 'view', 'name' => '查看'],
            ['key' => 'create', 'name' => '新增'],
            ['key' => 'update', 'name' => '修改'],
            ['key' => 'delete', 'name' => '删除'],
        ],
    ],
];
