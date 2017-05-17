<?php

namespace kriss\actions\rest;

class ErrorAction extends \kriss\actions\web\ErrorAction
{
    public function run()
    {
        return $this->renderAjaxResponse();
    }

    public function renderAjaxResponse()
    {
        return [
            'code' => $this->getExceptionCode(),
            'msg' => $this->getExceptionName() . ': ' . $this->getExceptionMessage()
        ];
    }
}