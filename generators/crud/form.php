<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator \kriss\generators\crud\Generator */

echo $form->field($generator, 'modelName');
echo $form->field($generator, 'modelLabel');
echo $form->field($generator, 'searchAttributes');

echo $form->field($generator, 'hasCreate')->checkbox();
echo $form->field($generator, 'hasView')->checkbox();
echo $form->field($generator, 'hasUpdate')->checkbox();
echo $form->field($generator, 'hasDelete')->checkbox();
echo $form->field($generator, 'useAjax')->checkbox();
echo $form->field($generator, 'hasCheckboxColumn')->checkbox();

echo $form->field($generator, 'modelPath');
echo $form->field($generator, 'controllerPath');
echo $form->field($generator, 'viewPath');
echo $form->field($generator, 'searchModelPath');
echo $form->field($generator, 'controllerBaseClass');
