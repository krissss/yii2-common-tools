<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator kriss\generators\model\Generator */

echo $this->render('@gii-model/form', [
    'form' => $form,
    'generator' => $generator
]);
echo $form->field($generator, 'generateDao')->checkbox();
echo $form->field($generator, 'daoNs');
