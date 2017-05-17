<?php

namespace kriss\actions\rest;

class OfflineAction extends \kriss\actions\web\OfflineAction
{
    public function run()
    {
        return $this->renderAjaxResponse();
    }
}