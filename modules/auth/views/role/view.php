<?php
/* @var $this yii\web\View */
/* @var $model \kriss\modules\auth\models\AuthRole */
/* @var $strOperation string */

use yii\widgets\DetailView;

$this->title = '角色详情';
$this->params['breadcrumbs'] = [
    '权限管理',
    [
        'label' => '角色管理',
        'url' => ['index'],
    ],
    $this->title,
];
?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title ?></h3>
    </div>
    <div class="box-body no-padding">
        <?php
        $attributes = [
            'id',
            'name',
            'description',
            [
                'attribute' => 'operation_list',
                'value' => $strOperation,
                'format' => 'html'
            ],
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $attributes,
            'options' => ['class' => 'table table-striped detail-view']
        ]) ?>
    </div>
</div>
