<?php

namespace kriss\widgets;

/**
 * @since 2.1.2
 */
class ExportMenuDataColumn extends \yii2tech\csvgrid\DataColumn
{
    public function renderDataCellContent($model, $key, $index)
    {
        // fix MS excel read error for long number
        // @link https://github.com/yii2tech/csv-grid/issues/23
        $value = parent::renderDataCellContent($model, $key, $index);
        return is_numeric($value) ? $value . "\t" : $value;
    }
}
