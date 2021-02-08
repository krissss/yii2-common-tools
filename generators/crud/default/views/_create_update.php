<?php
/**
 * @var yii\web\View $this
 * @var kriss\generators\crud\Generator $generator
 */

echo $this->render('create_update', [
    'generator' => $generator,
    'formClass' => 'SimpleAjaxForm'
]);
