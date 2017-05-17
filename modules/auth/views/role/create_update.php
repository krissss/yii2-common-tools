<?php
/* @var $this yii\web\View */
/* @var $model \kriss\modules\auth\models\AuthRole */
/* @var $operations array */

use kriss\widgets\SimpleActiveForm;
use yii\helpers\Html;

$this->title = ($model->isNewRecord ? '添加' : '修改') . '角色';
$this->params['breadcrumbs'] = [
    '权限管理',
    [
        'label' => '角色管理',
        'url' => ['/auth/role'],
    ],
    $this->title,
];

$form = SimpleActiveForm::begin();
echo $form->field($model, 'name')->textInput(['maxlength' => 64]);
echo $form->field($model, 'description')->textInput(['maxlength' => 255]);
?>
    <div class="row">
        <label class="control-label col-sm-2">选择权限</label>
        <div class="col-sm-8">
            <table class="table table-striped table-bordered">
                <tbody>
                <tr>
                    <th>模块</th>
                    <th>权限</th>
                </tr>
                <?php foreach ($operations as $operation) : ?>
                    <tr>
                        <td width="150px">
                            <?= Html::checkbox($operation['name'], false, ['label' => Yii::t('app', $operation['name']), 'id' => $operation['name'], 'suboperation' => implode(',', array_keys($operation['sub']))]) ?>
                            <?php
                            $str = '';
                            foreach ($operation['sub'] as $key => $value) {
                                $str .= '$("input[value=\'' . $key . '\']").prop("checked", this.checked);';
                            }
                            $this->registerJs('$("#' . $operation['name'] . '").click(function() {' . $str . '});');
                            ?>
                        </td>
                        <td><?= Html::activeCheckboxList($model, '_operations', $operation['sub'], ['unselect' => null]) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
echo $form->renderFooterButtons();
$form->end();
