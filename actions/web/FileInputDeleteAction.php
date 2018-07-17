<?php

namespace kriss\actions\web;

use Yii;
use yii\base\Action;
use yii\web\Response;

class FileInputDeleteAction extends Action
{
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['code' => 200];
    }
}
