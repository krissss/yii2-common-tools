<?php
/** @var $this yii\web\View */
/** @var $generator \kriss\generators\crud\Generator */

$searchAttributes = $generator->getSearchAttributes();
echo "<?php\n";
?>
/** @var $this yii\web\view */
/** @var $model <?= $generator->getModelClass() ?> */

use kriss\widgets\SimpleSearchForm;

$form = SimpleSearchForm::begin(['action' => ['index']]);

<?php foreach ($searchAttributes as $attribute): ?>
echo $form->field($model, '<?=$attribute?>');
<?php endforeach; ?>

echo $form->renderFooterButtons();

$form->end();
