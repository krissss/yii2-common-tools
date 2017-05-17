<?php

namespace kriss\components;

class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * 数组转存到数据库的字符串
     * @param $arr
     * @return string
     */
    protected function array2Str($arr)
    {
        // 去除空值
        if (is_array($arr)) {
            $arr = array_filter($arr);
            return ',' . implode(',,', $arr) . ',';
        }
        return $arr;
    }

    /**
     * 数据库字符串转数组
     * @param $str
     * @return array
     */
    protected function str2Arr($str)
    {
        // 如果为空直接返回空数组
        if (!$str) {
            return [];
        }
        return explode(',,', trim($str, ','));
    }

    /**
     * 获取名称
     * @param $index
     * @param $dataArr
     * @return string
     */
    protected function toName($index, $dataArr)
    {
        return isset($dataArr[$index]) ? $dataArr[$index] : '未知';
    }

    /**
     * 获取名称数组
     * @param $indexArr
     * @param $dataArr
     * @return array
     */
    protected function toNameArr($indexArr, $dataArr)
    {
        $nameArr = [];
        foreach ($indexArr as $index) {
            if (isset($dataArr[$index])) {
                $nameArr[] = $dataArr[$index];
            }
        }
        return $nameArr;
    }
}