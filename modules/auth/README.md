# config

## web `main.php`

```php
'modules' => [
    'auth' => \kriss\modules\auth\Module::className(),
],
'components' => [
    'user' => [
        'class' => \kriss\modules\auth\components\User::class,
        'authClass' => \common\models\base\Auth::class,
        'identityClass' => Admin::class,
        'enableAutoLogin' => true,
        'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
    ],
],
'controllerMap' => [
    'route-scan' => [
        'class' => \kriss\modules\auth\console\controllers\RouteScanController::class,
    ],
],
```

## console `main.php`

```php
'controllerMap' => [
    'init-auth' => [
        'class' => \kriss\modules\auth\console\controllers\InitAuthController::class,
        'adminClass' => 'common\models\Admin',
        'superAdminId' => 1,
        'authRoleAttribute' => 'auth_role',
        'authClass' => 'common\models\base\Auth',
    ],
    'auth-generator' => [
        'class' => \kriss\modules\auth\console\controllers\AuthGeneratorController::class,
        'genClass' => 'common\models\base\Auth',
        'configFile' => '@common/models/base/auth-config.php',
    ],
    'migrate' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationPath' => [
            '@app/migrations',
            '@vendor/kriss/modules/auth/migrations',
        ]
    ],
],
```

> migration is ```kriss\modules\auth\migrations\m170301_062150_auth```
you can use it or extend it and config some parameter

## actions for admin operate

set `UserRoleAction` to AdminController

```php
public function actions()
{
    $actions = parent::actions();

    $actions['user-role-update'] = [
        'class' => UserRoleUpdateAction::class,
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
```

# usage

## generate `auth-config.php`

visit http://user-site/route-scan/generate-file

'ok' will be show if `auth-config.php` is generated.

> for preview visit http://user-site/route-scan/list

## generate `Auth.php`

in console

```php
php yii auth-generator
```

## generate permission and role in `db`

in console

```php
# restore role and permission
php yii init-auth/restore
# update permissions only
php yii init-auth/update-operations
```

## configure permission roles and grant to admin

visit

permission: http://user-site/auth/permission

role: http://user-site/auth/role

user-role-update:  http://user-site/admin/user-role-update (admin is witch you set ```UserRoleUpdateAction```)

## configure permission for controller actions used

in `BaseController.php`

```php
use kriss\modules\auth\components\AuthRouteFilter;

public function behaviors()
{
    $behaviors = parent::behaviors();

    $behaviors['auth_route_filter'] = [
        'class' => AuthRouteFilter::class,
        'exceptRoutes' => [
            '*/preview-redirect'
        ],
    ];

    return $behaviors;
}
```

## configure permission for views used

- for menus

```php
$menus = AuthValidate::filterMenusRecursive([
    ['label' => '管理员管理', 'icon' => 'circle-o', 'url' => [$baseUrl . '/admin/index']],
    [
        'label' => '权限管理', 'icon' => 'list', 'url' => '#',
        'items' => [
            ['label' => '权限查看', 'icon' => 'circle-o', 'url' => ['/auth/permission/index']],
            ['label' => '角色管理', 'icon' => 'circle-o', 'url' => ['/auth/role/index']],
        ]
    ],
]);
```

> url must has default action.

- for DynaGrid columns

in `main.php`

```php
'container' => [
    'definitions' => [
        \kriss\widgets\ActionColumn::class => [
            'class' => \kriss\modules\auth\widgets\ActionColumn::class
        ],
        \kriss\widgets\ToggleColumn::class => [
            'class' => \kriss\modules\auth\widgets\ToggleColumn::class
        ],
    ],
],
```

> other column can be see in `widgets` dir

- for other view html

use `kriss\modules\auth\tools\AuthHtml` and `kriss\modules\auth\tools\AuthValidate`

eg:

```php
AuthHtml::a('新增', ['create'], ['class' => 'btn btn-success']);

AuthHtml::authTag('button', 'data-url', '批量删除', [
    'class' => 'btn btn-success check_operate_need_confirm',
    'data-url' => Url::to(['batch-delete']),
    'data-confirm-msg' => '确认全部删除?'
]);

AuthValidate::has(Auth::ADMIN__SHOP_GOODS__EXT_EXPORT);
```
