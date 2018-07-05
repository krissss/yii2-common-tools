<?php

namespace kriss\widgets;

use Yii;

class GroupAttributeColumn extends DataColumn
{
    /**
     * 'columns' => [
     *      'category.name:text:分组',
     *      'type.name:text:分类',
     *      'name:text:商品',
     *      ['attribute' => 'category.name', 'label' => '分组']
     *  ],
     * @var array
     */
    public $columns = [];

    public $labelValueSplit = ':';

    public $dataSplit = '<br>';

    public $label = '组合列';

    public $format = 'html';

    protected function renderDataCellContent($model, $key, $index)
    {
        $data = [];
        foreach ($this->columns as $columnConfig) {
            if (is_string($columnConfig)) {
                $column = $this->createDataColumn($columnConfig);
            } else {
                $column = Yii::createObject(array_merge([
                    'class' => DataColumn::class,
                    'grid' => $this->grid,
                ], $columnConfig));
            }
            $value = $column->renderDataCellContent($model, $key, $index);
            $label = $column->getHeaderCellLabel();
            $data[] = implode($this->labelValueSplit, [$label, $value]);
        }
        return implode($this->dataSplit, $data);
    }

    /**
     * @see \yii\grid\GridView
     */
    protected function createDataColumn($text)
    {
        if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new InvalidConfigException('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
        }

        return Yii::createObject([
            'class' => DataColumn::class,
            'grid' => $this->grid,
            'attribute' => $matches[1],
            'format' => isset($matches[3]) ? $matches[3] : 'text',
            'label' => isset($matches[5]) ? $matches[5] : null,
        ]);
    }
}
