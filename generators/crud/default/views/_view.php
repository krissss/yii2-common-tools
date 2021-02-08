<?php
/**
 * @var yii\web\View $this
 * @var kriss\generators\crud\Generator $generator
 */

echo $this->render('view', [
    'generator' => $generator,
    'formClass' => 'SimpleAjaxView'
]);
