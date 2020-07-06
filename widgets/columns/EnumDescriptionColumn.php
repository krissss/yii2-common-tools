<?php

namespace kriss\widgets\columns;

use kriss\enum\BaseEnum;

/**
 * in view columns
 * [
 *    'class' => EnumDescriptionColumn::class,
 *    'attribute' => 'status',
 * ]
 */
class EnumDescriptionColumn extends DataColumn
{
    /**
     * @var BaseEnum
     */
    public $enumClass;

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);
        return $this->enumClass::getDescription($value);
    }
}
