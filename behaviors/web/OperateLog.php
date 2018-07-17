<?php

namespace kriss\behaviors\web;

use kriss\components\OperateLogger;
use yii\base\ActionFilter;

class OperateLog extends ActionFilter
{
    /**
     * 日志分类
     * @var string
     */
    public $logCategory = 'app';

    public function beforeAction($action)
    {
        OperateLogger::logger($this->logCategory);
        return parent::beforeAction($action);
    }
}
