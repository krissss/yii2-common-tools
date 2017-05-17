<?php
/* @var $this yii\web\View */
/* @var $searchModel */
/* @var $dataProvider */

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
    [
        'attribute' => 'id',
        'hAlign' => 'center',
    ], [
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
        'buttons' => [
            'view' => function ($url) use ($authClass) {
                if (AuthValidate::has($authClass::ROLE_VIEW)) {
                    $options = [
                        'title' => '查看',
                        'data-toggle' => 'tooltip',
                    ];
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                }
                return '';
            },
            'update' => function ($url, $model) use ($authClass) {
                if (AuthValidate::has($authClass::ROLE_UPDATE) && $model->id !== 1) {
                    $options = [
                        'title' => '编辑',
                        'data-toggle' => 'tooltip',
                        'data-pjax' => '0',
                    ];
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                }
                return '';
            },
            'delete' => function ($url, $model) use ($authClass) {
                if (AuthValidate::has($authClass::ROLE_DELETE) && $model->id !== 1) {
                    $options = [
                        'title' => '删除',
                        'data-toggle' => 'tooltip',
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ];
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
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
                (AuthValidate::has($authClass::ROLE_CREATE) ? Html::a('<i class="fa fa-plus"></i>', ['create'], [
                    'class' => 'btn btn-default',
                    'title' => '添加',
                    'data-toggle' => 'tooltip',
                ]) : '')
        ],
    ],
]);
$simpleDynaGrid->renderDynaGrid();

