<?php

namespace app\kriss\widgets;

use yii2tech\csvgrid\DataColumn;

class ExportMenuDataColumn extends DataColumn
{
    public function renderDataCellContent($model, $key, $index)
    {
        // fix MS excel read error for long number
        // @link https://github.com/yii2tech/csv-grid/issues/23
        $value = parent::renderDataCellContent($model, $key, $index);
        return is_numeric($value) ? $value . "\t" : $value;
    }
}
