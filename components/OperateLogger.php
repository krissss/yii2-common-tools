<?php

namespace kriss\components;

use Yii;
use yii\base\Object;

class OperateLogger extends Object
{
    /**
     * 记录操作日志
     * 需要在 main 中配置 log
     * 推荐配置：
     * 文件记录：
     * [
        'class' => 'yii\log\FileTarget',
        'categories' => ['api', 'frontend', 'backend'],
        'logVars' => [],
        'logFile' => '@common/runtime/logs/operate/' . date('Y-m-d') . '.log',
        'maxLogFiles' => 93,
        ],
     * db 记录：
     * [
        'class' => 'yii\log\DbTarget',
        'categories' => ['api', 'frontend', 'backend'],
        'logVars' => [],
        ]
     * @param $logCategory string
     */
    public static function logger($logCategory)
    {
        $request = Yii::$app->request;
        $message = ([
            'absoluteUrl' => $request->absoluteUrl,
            'method' => $request->method,
            'referrer' => $request->referrer,
            'userAgent' => $request->userAgent,
            'userHost' => $request->userHost,
            'userIP' => $request->userIP,
            'rawBody' => urldecode($request->rawBody),
            'queryString' => urldecode($request->queryString),
        ]);
        Yii::info(json_encode($message), $logCategory);
    }
}