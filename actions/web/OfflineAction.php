<?php

namespace kriss\actions\web;

use Yii;
use yii\base\Action;
use yii\web\Response;

class OfflineAction extends Action
{
    /**
     * 视图布局
     * @var string
     */
    public $layout;
    /**
     * 视图文件地址
     * 默认使用对应控制器下的视图名称
     * @var string
     */
    public $view;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $message;
    /**
     * @var string
     */
    public $logo = '';
    /**
     * @var string
     */
    public $ajaxResponseFormat = Response::FORMAT_JSON;
    /**
     * @var string
     */
    public $postResponseFormat = Response::FORMAT_JSON;
    /**
     * @var int
     */
    public $httpCode = 503;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if ($this->name === null) {
            $this->name = '网站维护';
        }

        if ($this->message === null) {
            $this->message = '网站正在维护，请稍后访问';
        }
    }

    /**
     * Runs the action.
     *
     * @return array|string result content
     */
    public function run()
    {
        $request = Yii::$app->getRequest();
        if ($request->isAjax) {
            return $this->renderAjaxResponse();
        }
        if ($request->isPost) {
            return $this->renderPostResponse();
        }

        return $this->renderHtmlResponse();
    }

    /**
     * @return array
     */
    protected function renderAjaxResponse()
    {
        Yii::$app->response->format = $this->ajaxResponseFormat;
        return [
            'code' => $this->httpCode,
            'msg' => $this->name . ': ' . $this->message,
        ];
    }

    /**
     * @return array
     */
    protected function renderPostResponse()
    {
        Yii::$app->response->format = $this->postResponseFormat;
        return [
            'code' => $this->httpCode,
            'msg' => $this->name . ': ' . $this->message,
        ];
    }

    /**
     * @return string
     */
    protected function renderHtmlResponse()
    {
        if ($this->layout) {
            $this->controller->layout = $this->layout;
        }
        return $this->controller->render($this->view ?: $this->id, [
            'name' => $this->name,
            'message' => $this->message,
            'logo' => $this->logo,
        ]);
    }
}
