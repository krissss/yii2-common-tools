<?php
/**
 * @var $this yii\web\View
 * @var $searchModel
 * @var $dataProvider
 */

use kriss\modules\auth\models\AuthRole;
use kriss\modules\auth\Module;
use kriss\modules\auth\tools\AuthValidate;
use kriss\widgets\SimpleDynaGrid;
use yii\helpers\Html;

/** @var \kriss\modules\auth\models\Auth $authClass */
$authClass = Yii::$app->user->authClass;

$this->title = Yii::t('kriss', '角色管理');
$this->params['breadcrumbs'] = [
    Yii::t('kriss', '权限管理'),
    $this->title
];

$columns = [
    ['class' => 'kartik\grid\SerialColumn'],
    /*[
        'attribute' => 'id',
        'hAlign' => 'center',
    ],*/
    [
        'attribute' => 'name',
        'hAlign' => 'center',
        'enableSorting' => false,
        'value' => function (AuthRole $model) {
            return Yii::t('app', $model->name);
        },
    ], [
        'attribute' => 'description',
        'enableSorting' => false,
        'value' => function (AuthRole $model) {
            return Yii::t('app', $model->description);
        },
    ], /*[
        'attribute' => 'operation_list',
        'enableSorting' => false,
    ], */
    [
        'class' => 'kartik\grid\ActionColumn',
        'width' => '200px',
        'visibleButtons' => [
            'view' => AuthValidate::has($authClass::ROLE_VIEW),
            'update' => function ($model) use ($authClass) {
                return AuthValidate::has($authClass::ROLE_UPDATE) && (Module::getAuthRoleClass())::canLoginUserModify($model->id);
            },
            'delete' => function ($model) use ($authClass) {
                return AuthValidate::has($authClass::ROLE_DELETE) && (Module::getAuthRoleClass())::canLoginUserModify($model->id);
            },
        ],
        'buttons' => [
            'view' => function ($url) {
                $options = [
                    'class' => 'btn btn-default'
                ];
                return Html::a(Yii::t('kriss', '查看'), $url, $options);
            },
            'update' => function ($url) {
                $options = [
                    'data-pjax' => '0',
                    'class' => 'btn btn-default'
                ];
                return Html::a(Yii::t('kriss', '修改'), $url, $options);
            },
            'delete' => function ($url) {
                $options = [
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                    'class' => 'btn btn-danger'
                ];
                return Html::a(Yii::t('kriss', '删除'), $url, $options);
            }
        ]
    ],
];

echo SimpleDynaGrid::widget([
    'columns' => $columns,
    'dataProvider' => $dataProvider,
    //'searchModel' => $searchModel,
    'extraToolbar' => [
        [
            'content' =>
                (AuthValidate::has($authClass::ROLE_CREATE) ? Html::a(Yii::t('kriss', '新增'), ['create'], [
                    'class' => 'btn btn-default',
                ]) : '')
        ],
    ],
]);
