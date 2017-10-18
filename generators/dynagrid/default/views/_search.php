<?php
/** @var $this yii\web\View */
/** @var $generator \kriss\generators\dynagrid\Generator */

$searchAttributes = $generator->getSearchAttributes();
echo "<?php\n";
?>
/** @var $this yii\web\view */
/** @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */

use backend\widgets\SimpleSearchForm;

$form = SimpleSearchForm::begin(['action' => ['index']]);

<?php foreach ($searchAttributes as $attribute): ?>
echo $form->field($model, '<?=$attribute?>');
<?php endforeach; ?>

echo $form->renderFooterButtons();

$form->end();
