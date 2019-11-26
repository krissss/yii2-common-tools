<?php

namespace kriss\widgets;

use kriss\enum\BaseEnum;
use yii\base\BaseObject;
use yii\grid\DataColumn;
use yii\grid\SerialColumn;

/**
 * @since 2.1.2
 */
class ExportMenuHelper extends BaseObject
{
    /**
     * @var array
     */
    public $columns;
    /**
     * @var array
     */
    public $skipColumnClass = [
        'kartik\grid\ExpandRowColumn'
    ];
    /**
     * 转化的类和方法的映射关系，注意有前后顺序
     * @var array
     */
    public $transMap = [];
    /**
     * 默认的转化的类和方法的映射关系，注意有前后顺序
     * @var array
     */
    public $transMapDefault = [
        ToggleColumn::class => 'transToggleColumn',
        DatetimeColumn::class => 'transDatetimeColumn',
        ContentColumn::class => 'transSimpleColumn',
        GroupAttributeColumn::class => 'transGroupAttributeColumn',
        ImageViewColumn::class => 'transSimpleColumn',
        SerialColumn::class => 'transSerialColumn',
        LinkColumn::class => 'transSimpleColumn',
        EnumDescriptionColumn::class => 'transEnumColumn'
    ];
    /**
     * @var bool
     */
    public $transMapMergeDefault = true;
    /**
     * visible 为 false 时是否可以导出，默认不导出
     * @var bool
     */
    public $isVisibleFalseCanExport = false;

    public function init()
    {
        parent::init();
        if ($this->transMapMergeDefault) {
            $this->transMap = array_merge($this->transMapDefault, $this->transMap);
        }
    }

    public function trans()
    {
        $result = [];
        foreach ($this->columns as $column) {
            if (is_string($column)) {
                $result[] = $column;
                continue;
            }
            if (!isset($column['class'])) {
                $column = $this->transSimpleColumn($column);
                $result[] = $column;
                continue;
            }
            if ($this->isNeedSkip($column['class'])) {
                continue;
            }
            $oneResult = $this->transColumn($column);
            if ($oneResult !== false) {
                if (isset($oneResult['attribute']) || isset($oneResult['label'])) {
                    $result[] = $oneResult;
                } else {
                    // 二维数组，例如 GroupAttributeColumn 将返回多个 column
                    foreach ($oneResult as $item) {
                        $result[] = $item;
                    }
                }
                continue;
            }
            if ($this->isMatchClass($column['class'], DataColumn::class)) {
                $result[] = $this->transSimpleColumn($column);
            }
        }
        return $result;
    }

    protected function transColumn($column)
    {
        foreach ($this->transMap as $className => $method) {
            if ($this->isMatchClass($column['class'], $className)) {
                return call_user_func([$this, $method], $column);
            }
        }
        return false;
    }

    protected function isNeedSkip($class)
    {
        foreach ($this->skipColumnClass as $className) {
            if ($this->isMatchClass($class, $className)) {
                return true;
            }
        }
        return false;
    }

    protected function isMatchClass($class, $matchClass)
    {
        return is_a($class, $matchClass, true);
    }

    private $columnAttributeArgs = false;

    protected function transAttribute(array $column, array $newColumn)
    {
        if ($this->columnAttributeArgs === false) {
            $this->columnAttributeArgs = ['attribute', 'label', 'value', 'format'];
            if (!$this->isVisibleFalseCanExport) {
                $this->columnAttributeArgs[] = 'visible';
            }
        }
        foreach ($this->columnAttributeArgs as $arg) {
            if (isset($column[$arg])) {
                $newColumn[$arg] = $column[$arg];
            }
        }
        return $newColumn;
    }

    // 以下为各种 column 转化的方法

    public function transSimpleColumn(array $column)
    {
        $newColumn = [];
        $newColumn = $this->transAttribute($column, $newColumn);
        return $newColumn;
    }

    public function transToggleColumn(array $column)
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

    public function transDatetimeColumn(array $column)
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

    public function transGroupAttributeColumn(array $column)
    {
        $columnArr = [];
        foreach ($column['columns'] as $cColumn) {
            if (is_array($cColumn)) {
                if (isset($cColumn['class'])) {
                    $newColumn = $this->transColumn($cColumn);
                    if (isset($newColumn['attribute']) || isset($newColumn['label'])) {
                        $cColumn = $newColumn;
                    } else {
                        continue;
                    }
                } else {
                    $cColumn = $this->transSimpleColumn($cColumn);
                }
            }
            $columnArr[] = $cColumn;
        }
        return $columnArr;
    }

    public function transSerialColumn(array $column)
    {
        $newColumn = [
            'class' => \yii2tech\csvgrid\SerialColumn::class,
        ];
        if (isset($column['header'])) {
            $newColumn['header'] = $column['header'];
        }
        return $newColumn;
    }

    public function transEnumColumn(array $column)
    {
        $newColumn = [];
        /** @var BaseEnum $enumClass */
        $enumClass = $column['enumClass'];
        $newColumn = $this->transAttribute($column, $newColumn);
        $attribute = $column['attribute'];
        $newColumn['value'] = function ($model) use ($enumClass, $attribute) {
            return $enumClass::getDescription($model->$attribute);
        };
        return $newColumn;
    }

    /**
     * @param $column
     * @return array
     */
    protected function getToggleColumnItems($column)
    {
        if (isset($column['items'])) {
            return $column['items'];
        }
        return ToggleColumn::getDefaultItems();
    }
}
