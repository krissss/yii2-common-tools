<?php

namespace kriss\widgets;

use yii\helpers\Html;

class ImageViewColumn extends DataColumn
{
    public $imageWidth = 60;

    public $format = 'html';

    protected function renderDataCellContent($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);
        return Html::img($value, ['style' => "width: {$this->imageWidth}px;"]);
    }
}
