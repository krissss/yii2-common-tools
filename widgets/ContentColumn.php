<?php

namespace kriss\widgets;

use yii\helpers\Html;
use yii\helpers\StringHelper;

class ContentColumn extends DataColumn
{
    /**
     * @var int
     */
    public $maxLength = 20;
    /**
     * @var string
     */
    public $emptyValueShow = '';

    protected function renderDataCellContent($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);
        if (!$value || $value == $this->grid->formatter->nullDisplay) {
            return $this->emptyValueShow;
        }
        return Html::tag('span', StringHelper::truncate($value, $this->maxLength), ['title' => $value]);
    }
}
