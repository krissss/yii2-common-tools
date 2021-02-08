<?php
/**
 * @var yii\web\View $this
 * @var kriss\generators\crud\Generator $generator
 */

if (!isset($formClass)) {
    $formClass = 'SimpleBoxView';
}
$attributes = $generator->getColumnNames();
echo "<?php\n";
?>
/**
 * @var yii\web\view $this
 * @var <?= $generator->getModelClass() ?> $model
 */

use kriss\widgets\<?= $formClass ?>;
use yii\widgets\DetailView;

$this->title = '<?= $generator->modelLabel ?>详情';
<?php if($formClass == 'SimpleBoxView'): ?>
$this->params['breadcrumbs'] = [
    [
        'label' => '<?= $generator->modelLabel ?>列表',
        'url' => ['index']
    ],
    $this->title,
];
<?php endif; ?>

$widget = <?= $formClass ?>::begin(['header' => $this->title]);

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
<?php foreach ($attributes as $attribute): ?>
        '<?=$attribute?>',
<?php endforeach; ?>
    ]
]);

$widget->end();
