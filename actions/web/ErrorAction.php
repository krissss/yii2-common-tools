<?php
/**
 * 视图文件参考 views
 */
namespace kriss\actions\web;

class ErrorAction extends \yii\web\ErrorAction
{
    /**
     * @var string
     */
    public $layout;

    /**
     * 调整：添加可以配置 layout
     * @inheritdoc
     */
    protected function renderHtmlResponse()
    {
        if($this->layout){
            $this->controller->layout = $this->layout;
        }
        return parent::renderHtmlResponse();
    }
}