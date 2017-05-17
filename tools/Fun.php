<?php

namespace kriss\tools;

use Yii;

class Fun
{
    /**
     * 生成随机数
     * @param int $length
     * @return string
     */
    public static function generateRandString($length = 32)
    {
        $bytes = random_bytes($length); // php 7.0 具有的方法
        return strtr(substr(base64_encode($bytes), 0, $length), '+/', '_-');
    }

    /**
     * 格式化model的errors为字符串
     * @param $errors
     * @param string $childSplit
     * @param string $parentSplit
     * @return string
     */
    public static function formatModelErrors2String($errors, $childSplit = '', $parentSplit = '<br>')
    {
        $resultArr = [];
        foreach ($errors as $key => $error) {
            $resultArr[$key] = implode($childSplit, $error);
        }
        return implode($parentSplit, $resultArr);
    }

    /**
     * 获取第一个错误
     * @param $errors
     * @return string
     */
    public static function getFirstError($errors)
    {
        foreach ($errors as $key => $error) {
            return $error[0];
        }
        return '';
    }

    /**
     * 获取今日0点时间
     * @return int
     */
    public static function getToday0Time()
    {
        return self::getTimestamp0Time();
    }

    /**
     * 获取今日24点时间
     * @return int
     */
    public static function getToday24Time()
    {
        return self::getTimestamp24Time();
    }

    /**
     * 获取时间戳上的0点时间
     * @param null $timestamp
     * @return int
     */
    public static function getTimestamp0Time($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }
        return strtotime(date('Y-m-d 00:00:00', $timestamp));
    }

    /**
     * 获取时间戳上的24点时间
     * @param null $timestamp
     * @return int
     */
    public static function getTimestamp24Time($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }
        return strtotime(date('Y-m-d 23:59:59', $timestamp));
    }

    /**
     * 在后台执行 cmd
     * @param $cmd
     */
    public static function runCmdInBackground($cmd)
    {
        if (substr(php_uname(), 0, 7) == "Windows") {
            // windows 会自动关闭该打开的进程
            popen("start /B " . $cmd, "r");
        } else {
            exec($cmd . " > /dev/null &");
        }
    }

    /**
     * 在后台执行 console 下 controller 中的方法
     * @param $yiiCmd
     */
    public static function runYiiConsoleInBackground($yiiCmd)
    {
        $yiiExecPath = dirname(Yii::getAlias('@console'));
        $command = 'php ' . $yiiExecPath . DIRECTORY_SEPARATOR . 'yii ' . $yiiCmd;
        static::runCmdInBackground($command);
    }
}