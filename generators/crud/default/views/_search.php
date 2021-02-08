<?php
/**
 * @var yii\web\View $this
 * @var kriss\generators\crud\Generator $generator
 */

$searchAttributes = $generator->getSearchAttributes();
echo "<?php\n";
?>
/**
 * @var yii\web\view $this
 * @var <?= $generator->getSearchClass() ?> $model
 */

use kriss\widgets\SimpleSearchForm;

$form = SimpleSearchForm::begin(['action' => ['index']]);

<?php foreach ($searchAttributes as $attribute): ?>
echo $form->field($model, '<?=$attribute?>');
<?php endforeach; ?>

$form->end();
