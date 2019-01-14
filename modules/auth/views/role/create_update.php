<?php
/**
 * @var $this yii\web\View
 * @var $model \kriss\modules\auth\models\AuthRole
 * @var $operations array
 */

use kriss\modules\auth\models\AuthOperation;
use kriss\widgets\SimpleActiveForm;
use yii\helpers\Html;

$this->title = ($model->isNewRecord ?
        Yii::t('kriss', '添加') : Yii::t('kriss', '修改'))
    . Yii::t('kriss', '角色');
$this->params['breadcrumbs'] = [
    Yii::t('kriss', '权限管理'),
    [
        'label' => Yii::t('kriss', '角色管理'),
        'url' => ['/auth/role'],
    ],
    $this->title,
];

$form = SimpleActiveForm::begin([
    'title' => $this->title
]);
echo $form->field($model, 'name')->textInput(['maxlength' => 64]);
echo $form->field($model, 'description')->textInput(['maxlength' => 255]);
?>
    <div class="row">
        <label class="control-label col-sm-2"><?= Yii::t('kriss', '选择权限') ?></label>
        <div class="col-sm-8">
            <table class="table table-striped table-bordered">
                <tbody>
                <tr>
                    <th><?= Yii::t('kriss', '模块') ?></th>
                    <th><?= Yii::t('kriss', '权限') ?></th>
                </tr>
                <?php foreach ($operations as $operation) : ?>
                    <?php
                    /** @var AuthOperation $module */
                    $module = $operation['model'];
                    /** @var AuthOperation[] $items */
                    $items = $operation['items'];
                    $subItems = [];
                    foreach ($items as $item) {
                        $subItems[$item->name] = $item->getViewName();
                    }
                    ?>
                    <tr>
                        <td width="150px">
                            <?= Html::activeCheckboxList($model, '_operations', [
                                $module->name => $module->getViewName()
                            ], ['encode' => false, 'data-toggle' => 'checkbox-parent', 'unselect' => null]) ?>
                        </td>
                        <td><?= Html::activeCheckboxList($model, '_operations', $subItems, ['encode' => false, 'data-toggle' => 'checkbox-item', 'unselect' => null]) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
$form->end();

$js = <<<JS
function changeSubCheckBox(parentCheck) {
  parentCheck.parents('td').next('td').find('input[type=checkbox]').prop('checked', parentCheck.prop('checked'));
}
var parentEl = $('[data-toggle="checkbox-parent"] input[type=checkbox]');
var itemEl = $('[data-toggle="checkbox-item"] input[type=checkbox]');
parentEl.each(function() {
  if ($(this).prop('checked') === true) {
    changeSubCheckBox($(this));
  }
});
parentEl.change(function() {
  changeSubCheckBox($(this));
});
itemEl.change(function() {
  if ($(this).prop('checked') === false) {
    $(this).parents('td').prev('td').find('input[type=checkbox]').prop('checked', false);
  }
});
JS;
$this->registerJs($js);
