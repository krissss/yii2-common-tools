<?php

namespace kriss\widgets;

use yii\grid\DataColumn;
use yii\grid\SerialColumn;

/**
 * @since 2.1.2
 */
class ExportMenuHelper
{
    public static function transColumns($columns)
    {
        $result = [];
        foreach ($columns as $column) {
            if (isset($column['class'])) {
                if (static::isNeedSkip($column['class'])) {
                    continue;
                }
                if (static::isMatchClass($column['class'], ToggleColumn::class)) {
                    $result[] = static::transToggleColumn($column);
                } elseif (static::isMatchClass($column['class'], DatetimeColumn::class)) {
                    $result[] = static::transDatetimeColumn($column);
                } elseif (static::isMatchClass($column['class'], ContentColumn::class)) {
                    $result[] = static::transSimpleColumn($column);
                } elseif (static::isMatchClass($column['class'], GroupAttributeColumn::class)) {
                    $columnArr = static::transGroupAttributeColumn($column);
                    foreach ($columnArr as $newColumn) {
                        if (is_array($newColumn)) {
                            $result[] = static::transSimpleColumn($newColumn);
                        } else {
                            $result[] = $newColumn;
                        }
                    }
                } elseif (static::isMatchClass($column['class'], ImageViewColumn::class)) {
                    $result[] = static::transSimpleColumn($column);
                } elseif (static::isMatchClass($column['class'], SerialColumn::class)) {
                    $result[] = static::transSerialColumn($column);
                } elseif (static::isMatchClass($column['class'], LinkColumn::class)) {
                    $result[] = static::transSimpleColumn($column);
                } elseif (static::isMatchClass($column['class'], DataColumn::class)) {
                    // 此项必须放在最后
                    $result[] = static::transSimpleColumn($column);
                }
            } else {
                $column = static::transSimpleColumn($column);
                $result[] = $column;
            }
        }
        return $result;
    }

    protected static function isNeedSkip($class)
    {
        $skipClassArr = [
            'kartik\grid\ExpandRowColumn'
        ];
        foreach ($skipClassArr as $className) {
            if (static::isMatchClass($class, $className)) {
                return true;
            }
        }
        return false;
    }

    protected static function transSimpleColumn(array $column)
    {
        $newColumn = [];
        $newColumn = static::transAttribute($column, $newColumn);
        return $newColumn;
    }

    protected static function transToggleColumn(array $column)
    {
        $newColumn = [];
        $newColumn = static::transAttribute($column, $newColumn);
        $items = $column['items'];
        $attribute = $column['attribute'];
        $newColumn['value'] = function ($model) use ($items, $attribute) {
            return $items[$model->$attribute];
        };
        return $newColumn;
    }

    protected static function transDatetimeColumn(array $column)
    {
        $newColumn = [];
        $newColumn = static::transAttribute($column, $newColumn);
        $dateFormat = 'Y-m-d H:i:s';
        if (isset($column['dateFormat'])) {
            $dateFormat = $column['dateFormat'];
        }
        $newColumn['format'] = ['datetime', 'php:' . $dateFormat];
        return $newColumn;
    }

    protected static function transGroupAttributeColumn(array $column)
    {
        $columnArr = [];
        foreach ($column['columns'] as $column) {
            if (is_array($column) && isset($column['class'])) {
                $newColumn = [];
                $newColumn = static::transAttribute($column, $newColumn);
                $newColumn['label'] .= "(${$column['label']})";
                $column = $newColumn;
            }
            $columnArr[] = $column;
        }
        return $columnArr;
    }

    protected static function transSerialColumn(array $column)
    {
        $newColumn = [
            'class' => \yii2tech\csvgrid\SerialColumn::class,
        ];
        if (isset($column['header'])) {
            $newColumn['header'] = $column['header'];
        }
        return $newColumn;
    }

    protected static function isMatchClass($class, $matchClass)
    {
        return is_a($class, $matchClass, true);
    }

    protected static function transAttribute($column, $newColumn)
    {
        $args = ['attribute', 'label', 'value'];
        foreach ($args as $arg) {
            if (isset($column[$arg])) {
                $newColumn[$arg] = $column[$arg];
            }
        }
        return $newColumn;
    }
}
