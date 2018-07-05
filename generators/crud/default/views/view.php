<?php
/** @var $this yii\web\View */
/** @var $generator \kriss\generators\crud\Generator */

if (!isset($formClass)) {
    $formClass = 'SimpleBoxView';
}
$attributes = $generator->getColumnNames();
echo "<?php\n";
?>
/** @var $this yii\web\view */
/** @var $model <?= $generator->getModelClass() ?> */

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

<?= $formClass ?>::begin(['header' => $this->title]);

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
<?php foreach ($attributes as $attribute): ?>
        '<?=$attribute?>',
<?php endforeach; ?>
    ]
]);

<?= $formClass ?>::end();
