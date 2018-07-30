<?php

namespace kriss\widgets;

use Yii;
use yii\base\InvalidConfigException;

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
    /**
     * @var string
     */
    public $labelValueSplit = ':';
    /**
     * @var string
     */
    public $dataSplit = '<br>';
    /**
     * @var string
     */
    public $label = '组合列';
    /**
     * @var string
     */
    public $format = 'html';
    /**
     * 若某一列为空时显示内容
     * false 代表不显示
     * @var false|string
     */
    public $emptyValue = false;

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
            if ($value == $column->grid->formatter->nullDisplay) {
                if ($this->emptyValue === false) {
                    continue;
                } else {
                    $value = $this->emptyValue;
                }
            }
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
