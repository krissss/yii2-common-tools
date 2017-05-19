# config

main.php

    'modules' => [
        'auth' => \kriss\modules\auth\Module::className()
    ],
    'components' => [
        'user' => [
            'class' => \backend\modules\auth\components\User::className(),
            'authClass' => \common\models\base\Auth::className(),
            'identityClass' => Admin::className(),
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
    ]
    
set UserRoleAction to AdminController

    public function actions()
    {
        $actions = parent::actions();

        $actions['user-role-update'] = [
            'class' => UserRoleUpdateAction::className(),
            'isRenderAjax' => true,
            'view' => '_update_role',
            'successCallback' => function ($action, $result) {
                /** @var $action UserRoleUpdateAction */
                if ($result['type'] == 'success') {
                    Yii::$app->session->setFlash('success', '修改成功');
                } else {
                    Yii::$app->session->setFlash('error', '修改失败：' . $result['msg']);
                }
                return $action->controller->redirect(['index']);
            }
        ];

        return $actions;
    }

# db migration

migration is ```kriss\modules\auth\migrations\m170301_062150_auth```

you can use it or extend it and config some parameter

# console init

console init is ```kriss\modules\auth\console\controllers\InitAuthController```

you can use it or extend it and config some parameter
    
# visit

permission: http://user-site/auth/permission

role: http://user-site/auth/role

user-role-update:  http://user-site/admin/user-role-update (admin is witch you set ```UserRoleUpdateAction```)