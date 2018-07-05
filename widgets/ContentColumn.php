<?php

namespace kriss\widgets;

use yii\helpers\Html;
use yii\helpers\StringHelper;

class ContentColumn extends DataColumn
{
    public $maxLength = 20;

    protected function renderDataCellContent($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);
        return Html::tag('span', StringHelper::truncate($value, $this->maxLength), ['title' => $value]);
    }
}
