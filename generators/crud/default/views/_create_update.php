<?php
/** @var $this yii\web\View */
/** @var $generator \kriss\generators\crud\Generator */

echo $this->render('create_update', [
    'generator' => $generator,
    'formClass' => 'SimpleAjaxForm'
]);
