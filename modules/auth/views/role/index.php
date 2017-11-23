<?php
/* @var $this yii\web\View */
/* @var $searchModel */
/* @var $dataProvider */

use kriss\modules\auth\Module;
use yii\helpers\Html;
use kriss\modules\auth\tools\AuthValidate;

/** @var \kriss\modules\auth\models\Auth $authClass */
$authClass = Yii::$app->user->authClass;

$this->title = '角色管理';
$this->params['breadcrumbs'] = [
    '权限管理',
    $this->title
];

$columns = [
    ['class' => 'kartik\grid\SerialColumn'],
    /*[
        'attribute' => 'id',
        'hAlign' => 'center',
    ],*/ [
        'attribute' => 'name',
        'hAlign' => 'center',
        'enableSorting' => false,
    ], [
        'attribute' => 'description',
        'enableSorting' => false,
    ], /*[
        'attribute' => 'operation_list',
        'enableSorting' => false,
    ], */
    [
        'class' => 'kartik\grid\ActionColumn',
        'width' => '200px',
        'buttons' => [
            'view' => function ($url) use ($authClass) {
                if (AuthValidate::has($authClass::ROLE_VIEW)) {
                    $options = [
                        'class' => 'btn btn-default'
                    ];
                    return Html::a('查看', $url, $options);
                }
                return '';
            },
            'update' => function ($url, $model) use ($authClass) {
                if (AuthValidate::has($authClass::ROLE_UPDATE) && (Module::getAuthRoleClass())::canLoginUserModify($model->id)) {
                    $options = [
                        'data-pjax' => '0',
                        'class' => 'btn btn-default'
                    ];
                    return Html::a('修改', $url, $options);
                }
                return '';
            },
            'delete' => function ($url, $model) use ($authClass) {
                if (AuthValidate::has($authClass::ROLE_DELETE) && (Module::getAuthRoleClass())::canLoginUserModify($model->id)) {
                    $options = [
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                        'class' => 'btn btn-danger'
                    ];
                    return Html::a('删除', $url, $options);
                }
                return '';
            }
        ]
    ],
];

$simpleDynaGrid = new \kriss\widgets\SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-auth-role-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    //'searchModel' => $searchModel,
    'extraToolbar' => [
        [
            'content' =>
                (AuthValidate::has($authClass::ROLE_CREATE) ? Html::a('新增', ['create'], [
                    'class' => 'btn btn-default',
                ]) : '')
        ],
    ],
]);
$simpleDynaGrid->renderDynaGrid();

