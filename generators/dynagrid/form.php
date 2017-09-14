<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator \kriss\generators\dynagrid\Generator */

echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'controllerBaseClass');
echo $form->field($generator, 'activeDataProviderClass');
echo $form->field($generator, 'searchModelClass');
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'searchAttributes');
echo $form->field($generator, 'actionIndex');
echo $form->field($generator, 'title');
echo $form->field($generator, 'dataColumns');
echo $form->field($generator, 'actionColumns');
