<?php
/**
 * @var $this \yii\web\View
 * @var $model \kriss\modules\auth\models\UpdateUserRole
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$displayInfo = $model->getDisplayInfo();

?>
<div class="modal fade ajax_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $form = ActiveForm::begin([
                'options' => [
                    'class' => 'form-horizontal'
                ],
                'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-10">{input}</div>{error}',
                    'labelOptions' => ['class' => 'control-label col-sm-2'],
                    'errorOptions' => ['class' => 'help-block col-sm-10']
                ]
            ]);
            ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?= Yii::t('kriss', '修改用户角色') ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label col-sm-2"><?= $displayInfo['label'] ?></label>
                    <div class="col-sm-10">
                        <p class="form-control"><?= $displayInfo['value'] ?></p>
                    </div>
                </div>
                <?= Html::activeCheckboxList($model, 'userRole', $model->roles); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('kriss', '取消') ?></button>
                <button type="submit" class="btn btn-primary"><?= Yii::t('kriss', '提交') ?></button>
            </div>
            <?php $form->end(); ?>
        </div>
    </div>
</div>
