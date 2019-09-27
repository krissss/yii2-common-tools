<?php

namespace kriss\actions\web;

class ErrorAction extends \yii\web\ErrorAction
{
    /**
     * @var string
     */
    public $layout;
    /**
     * @var string
     */
    public $logo = '';

    /**
     * 调整：添加可以配置 layout
     * @inheritdoc
     */
    protected function renderHtmlResponse()
    {
        if ($this->layout) {
            $this->controller->layout = $this->layout;
        }
        return parent::renderHtmlResponse();
    }

    /**
     * @inheritDoc
     */
    protected function getViewRenderParams()
    {
        return array_merge(parent::getViewRenderParams(), [
            'logo' => $this->logo,
        ]);
    }
}
