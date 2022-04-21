<?php

namespace kriss\widgets;

class DatetimeColumn extends DataColumn
{
    /**
     * 日期格式
     * @var string
     */
    public $dateFormat = 'Y-m-d H:i:s';
    /**
     * 跳过空值
     * @var bool
     */
    public $skipEmptyValue = true;
    /**
     * 空值的定义
     * @var array
     */
    public $emptyValue = [
        0, '0', null, '',
    ];
    /**
     * 空值显示内容
     * @var string
     */
    public $emptyValueShow = '';

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);
        if ($this->skipEmptyValue && in_array($value, $this->emptyValue, true)) {
            return $this->emptyValueShow;
        } else {
            return date($this->dateFormat, $value);
        }
    }
}
