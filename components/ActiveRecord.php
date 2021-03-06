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
        if (is_array($arr)) {
            // 去除空值
            $arr = array_filter($arr);
            if ($arr) {
                return ',' . implode(',,', $arr) . ',';
            } else {
                return '';
            }
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
     * @param $index string|integer
     * @param $dataArr array
     * @param $notExistValue string
     * @return string
     */
    protected function toName($index, $dataArr, $notExistValue = '未知')
    {
        return isset($dataArr[$index]) ? $dataArr[$index] : $notExistValue;
    }

    /**
     * 获取名称数组
     * @param $indexArr array
     * @param $dataArr array
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
