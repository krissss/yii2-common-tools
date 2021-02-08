<?php
/**
 * @var yii\web\View $this
 * @var kriss\generators\crud\Generator $generator
 */

if (!isset($formClass)) {
    $formClass = 'SimpleActiveForm';
}
$attributes = $generator->getColumnNames();
echo "<?php\n";
?>
/**
 * @var yii\web\view $this
 * @var <?= $generator->getModelClass() ?> $model 
 */

use kriss\widgets\<?= $formClass ?>;

$this->title = $model->isNewRecord ? '新增<?= $generator->modelLabel ?>' : '修改<?= $generator->modelLabel ?>';
<?php if($formClass == 'SimpleActiveForm'): ?>
$this->params['breadcrumbs'] = [
    [
        'label' => '<?= $generator->modelLabel ?>列表',
        'url' => ['index']
    ],
    $this->title,
];
<?php endif; ?>

$form = <?= $formClass ?>::begin(['header' => $this->title]);

<?php foreach ($attributes as $attribute): ?>
echo $form->field($model, '<?=$attribute?>');
<?php endforeach; ?>

$form->end();
