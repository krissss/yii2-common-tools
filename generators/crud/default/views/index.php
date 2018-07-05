<?php
/** @var $this yii\web\View */
/** @var $generator \kriss\generators\crud\Generator */

$hasActionColumn = $generator->hasView || $generator->hasUpdate || $generator->hasDelete;
$hasToolbar = $generator->hasCreate;
echo "<?php\n";
?>
/** @var $this yii\web\View */
/** @var $dataProvider */
<?php if(!empty($generator->searchModelClass)): ?>
/** @var $searchModel */
<?php endif; ?>

<?php if($hasActionColumn): ?>
use kriss\widgets\ActionColumn;
<?php endif; ?>
use kriss\widgets\SimpleDynaGrid;
<?php if($hasToolbar): ?>
use yii\helpers\Html;
<?php endif; ?>

$this->title = '<?=$generator->modelLabel?>列表';
$this->params['breadcrumbs'] = [
    $this->title,
];

<?php if($generator->searchAttributes): ?>
echo $this->render('_search', [
    'model' => $searchModel,
]);

<?php endif; ?>
$columns = [
<?php foreach ($generator->getColumnNames() as $column): ?>
    [
        'attribute' => '<?=$column?>',
    ],
<?php endforeach; ?>
<?php if($hasActionColumn): ?>
    [
        'class' => ActionColumn::class,
        'groupButtons' => [
<?php if($generator->hasView): ?>
            ['action' => 'view', 'label' => '详情',<?= $generator->useAjax ? " 'cssClass' => 'show_ajax_modal'," : '' ?>],
<?php endif; ?>
<?php if($generator->hasUpdate): ?>
            ['action' => 'update', 'label' => '修改', 'type' => 'primary',<?= $generator->useAjax ? " 'cssClass' => 'show_ajax_modal'," : '' ?>],
<?php endif; ?>
<?php if($generator->hasDelete): ?>
            ['action' => 'delete', 'label' => '删除', 'type' => 'danger', 'options' => ['data-confirm' => '确定删除？'],],
<?php endif; ?>
        ],
    ],
<?php endif; ?>
<?php if($generator->hasCheckboxColumn): ?>
    [
        'class' => '\kartik\grid\CheckboxColumn',
    ],
<?php endif; ?>
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-<?=$generator->getControllerID()?>-index',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
<?php if($hasToolbar): ?>
    'extraToolbar' => [
        [
            'content' => Html::a('新增', ['create'], ['class' => 'btn btn-primary<?= $generator->useAjax ? ' show_ajax_modal' : '' ?>'])
        ]
    ]
<?php endif; ?>
]);
$simpleDynaGrid->renderDynaGrid();
