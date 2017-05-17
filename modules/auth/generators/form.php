<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator \kriss\modules\auth\generators\Generator */

echo $form->field($generator, 'authClass');
echo $form->field($generator, 'moduleId');
echo $form->field($generator, 'moduleKey');
echo $form->field($generator, 'moduleName');
echo $form->field($generator, 'childOperations');
echo $form->field($generator, 'useModulePrefix')->checkbox([
    true => 'yes',
    false => 'no'
]);
echo $form->field($generator, 'baseClass');
