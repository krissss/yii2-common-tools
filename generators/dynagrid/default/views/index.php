<?php
/**
 * @var yii\web\View $this 
 * @var kriss\generators\dynagrid\Generator $generator
 * @var string $action the action ID
 */

$actionColumns = $generator->getActionColumns();
$hasActionColumn = (bool)$actionColumns;

$toolbarActions = $generator->getToolbarActions();
$hasToolbarAction = (bool)$toolbarActions;

echo "<?php\n";
?>
/**
 * @var yii\web\View $this
 * @var <?= ltrim($generator->activeDataProviderClass, '\\') ?> $dataProvider
<?php if(!empty($generator->searchModelClass)): ?>
 * @var <?= ltrim($generator->searchModelClass, '\\') ?> $searchModel
<?php endif; ?>
 */

use backend\widgets\SimpleDynaGrid;
<?php if($hasActionColumn || $hasToolbarAction): ?>
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
        'width' => '150px',
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
<?php if($generator->hasCheckboxColumn): ?>
    [
        'class' => '\kartik\grid\CheckboxColumn',
    ],
<?php endif; ?>
];

echo SimpleDynaGrid::widget([
    'dataProvider' => $dataProvider,
    'columns' => $columns,
<?php if($hasToolbarAction): ?>
<?php
$toolbarActionsStrArr = [];
foreach ($toolbarActions as $url => $label) {
    $toolbarActionsStrArr[] = "Html::a('{$label}', ['{$url}'], ['class' => 'btn btn-default'])";
}
$toolbarActionsStr = implode("\n            . ", $toolbarActionsStrArr);
?>
    'extraToolbar' => [
        [
            'content' => <?= $toolbarActionsStr . "\n"; ?>
        ]
    ]
<?php endif; ?>
]);
