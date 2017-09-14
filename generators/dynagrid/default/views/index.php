<?php
/* @var $this yii\web\View */
/* @var $generator \kriss\generators\dynagrid\Generator */
/* @var $action string the action ID */

$actionColumns = $generator->getActionColumns();
$hasActionColumn = (bool)$actionColumns;

echo "<?php\n";
?>
/* @var $this yii\web\View */
/* @var $dataProvider <?= ltrim($generator->activeDataProviderClass, '\\') ?> */
<?php if(!empty($generator->searchModelClass)): ?>
/* @var $searchModel <?= ltrim($generator->searchModelClass, '\\') ?> */
<?php endif; ?>

use backend\widgets\SimpleDynaGrid;
<?php if($hasActionColumn): ?>
use yii\helpers\Html;
<?php endif; ?>

$this->title = '<?=$generator->title?>列表';
$this->params['breadcrumbs'] = [
    '<?=$generator->title?>',
    $this->title,
];

<?php if(!empty($generator->searchModelClass)): ?>
echo $this->render('_search', [
    'model' => $searchModel,
]);

<?php endif; ?>
$columns = [
<?php foreach ($generator->getDataColumns() as $column): ?>
    [
        'attribute' => '<?=$column?>',
    ],
<?php endforeach; ?>
<?php if($hasActionColumn): ?>
    [
        'class' => '\kartik\grid\ActionColumn',
        'template' => '<?= implode(' ', array_map(function ($v){ return '{'.$v.'}'; }, array_keys($actionColumns))) ?>',
        'buttons' => [
<?php foreach ($actionColumns as $column => $label): ?>
            '<?=$column?>' => function ($url) {
                $options = [
                    'class' => 'btn btn-default',
                ];
                return Html::a('<?=$label?>', $url, $options);
            },
<?php endforeach; ?>
        ],
    ],
<?php endif; ?>
];

$simpleDynaGrid = new SimpleDynaGrid([
    'dynaGridId' => 'dynagrid-<?=$generator->getControllerID()?>-<?=$generator->actionIndex?>',
    'columns' => $columns,
    'dataProvider' => $dataProvider,
]);
$simpleDynaGrid->renderDynaGrid();
