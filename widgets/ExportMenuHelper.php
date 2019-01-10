<?php

namespace kriss\widgets;

use yii\grid\DataColumn;
use yii\grid\SerialColumn;

/**
 * @since 2.1.2
 */
class ExportMenuHelper
{
    public $columns;

    public function __construct($columns)
    {
        $this->columns = $columns;
    }

    public static function create($columns)
    {
        return new static($columns);
    }

    public static function transColumns($columns)
    {
        $self = static::create($columns);
        return $self->trans();
    }

    public function trans()
    {
        $result = [];
        foreach ($this->columns as $column) {
            if (is_string($column)) {
                $result[] = $column;
                continue;
            }
            if (isset($column['class'])) {
                if ($this->isNeedSkip($column['class'])) {
                    continue;
                }
                if ($this->isMatchClass($column['class'], ToggleColumn::class)) {
                    $result[] = $this->transToggleColumn($column);
                } elseif ($this->isMatchClass($column['class'], DatetimeColumn::class)) {
                    $result[] = $this->transDatetimeColumn($column);
                } elseif ($this->isMatchClass($column['class'], ContentColumn::class)) {
                    $result[] = $this->transSimpleColumn($column);
                } elseif ($this->isMatchClass($column['class'], GroupAttributeColumn::class)) {
                    $columnArr = $this->transGroupAttributeColumn($column);
                    foreach ($columnArr as $newColumn) {
                        $result[] = is_array($newColumn) ? $this->transSimpleColumn($newColumn) : $newColumn;
                    }
                } elseif ($this->isMatchClass($column['class'], ImageViewColumn::class)) {
                    $result[] = $this->transSimpleColumn($column);
                } elseif ($this->isMatchClass($column['class'], SerialColumn::class)) {
                    $result[] = $this->transSerialColumn($column);
                } elseif ($this->isMatchClass($column['class'], LinkColumn::class)) {
                    $result[] = $this->transSimpleColumn($column);
                } elseif ($this->isMatchClass($column['class'], DataColumn::class)) {
                    // 此项必须放在最后
                    $result[] = $this->transSimpleColumn($column);
                }
            } else {
                $column = $this->transSimpleColumn($column);
                $result[] = $column;
            }
        }
        return $result;
    }

    protected function isNeedSkip($class)
    {
        $skipClassArr = [
            'kartik\grid\ExpandRowColumn',
        ];
        foreach ($skipClassArr as $className) {
            if ($this->isMatchClass($class, $className)) {
                return true;
            }
        }
        return false;
    }

    protected function transSimpleColumn(array $column)
    {
        $newColumn = [];
        $newColumn = $this->transAttribute($column, $newColumn);
        return $newColumn;
    }

    protected function transToggleColumn(array $column)
    {
        $newColumn = [];
        $newColumn = $this->transAttribute($column, $newColumn);
        $items = $this->getToggleColumnItems($column);
        $attribute = $column['attribute'];
        $newColumn['value'] = function ($model) use ($items, $attribute) {
            return $items[$model->$attribute];
        };
        return $newColumn;
    }

    protected function transDatetimeColumn(array $column)
    {
        $newColumn = [];
        $newColumn = $this->transAttribute($column, $newColumn);
        $dateFormat = 'Y-m-d H:i:s';
        if (isset($column['dateFormat'])) {
            $dateFormat = $column['dateFormat'];
        }
        $newColumn['format'] = ['datetime', 'php:' . $dateFormat];
        return $newColumn;
    }

    protected function transGroupAttributeColumn(array $column)
    {
        $columnArr = [];
        foreach ($column['columns'] as $column) {
            if (is_array($column) && isset($column['class'])) {
                $newColumn = [];
                $newColumn = $this->transAttribute($column, $newColumn);
                $newColumn['label'] .= "({$column['label']})";
                $column = $newColumn;
            }
            $columnArr[] = $column;
        }
        return $columnArr;
    }

    protected function transSerialColumn(array $column)
    {
        $newColumn = [
            'class' => \yii2tech\csvgrid\SerialColumn::class,
        ];
        if (isset($column['header'])) {
            $newColumn['header'] = $column['header'];
        }
        return $newColumn;
    }

    protected function isMatchClass($class, $matchClass)
    {
        return is_a($class, $matchClass, true);
    }

    protected function transAttribute($column, $newColumn)
    {
        $args = ['attribute', 'label', 'value', 'format'];
        foreach ($args as $arg) {
            if (isset($column[$arg])) {
                $newColumn[$arg] = $column[$arg];
            }
        }
        return $newColumn;
    }

    /**
     * @param $column
     * @return array
     */
    private function getToggleColumnItems($column)
    {
        if (isset($column['items'])) {
            return $column['items'];
        }
        return ToggleColumn::getDefaultItems();
    }
}
