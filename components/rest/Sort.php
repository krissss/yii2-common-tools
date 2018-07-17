<?php

namespace kriss\components\rest;

class Sort extends \yii\data\Sort
{
    /**
     * @var bool
     */
    public $enableMultiSort = true;

    /**
     * 主键参数，用于排序
     * @var string
     */
    public $primaryKeyParam = 'id';
    /**
     * 默认主键排序
     * @var int
     */
    public $primaryKeyDefaultSort = SORT_ASC;

    /**
     * 增加默认追加排序字段 id
     * @param string $param
     * @return array
     */
    public function parseSortParam($param)
    {
        $result = parent::parseSortParam($param);
        // 增加 id 字段排序，防止 mysql 使用单个字段排序时分页有问题
        if (isset($this->attributes[$this->primaryKeyParam])) {
            if (!in_array($this->primaryKeyParam, $result) && !in_array('-' . $this->primaryKeyParam, $result)) {
                array_push($result, $this->primaryKeyDefaultSort == SORT_ASC ? $this->primaryKeyParam : ('-' . $this->primaryKeyParam));
            }
        }
        return $result;
    }
}
