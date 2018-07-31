<?php

namespace kriss\widgets;

use yii\helpers\Html;

class ImageViewColumn extends DataColumn
{
    /**
     * @var int
     */
    public $imageWidth = 60;
    /**
     * @var string
     */
    public $format = 'html';
    /**
     * 空值显示内容
     * @var string
     */
    public $emptyValueShow = '';

    protected function renderDataCellContent($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);
        if (!$value || $value === $this->grid->formatter->nullDisplay) {
            return $this->emptyValueShow;
        }
        return Html::img($value, ['style' => "width: {$this->imageWidth}px;"]);
    }
}
