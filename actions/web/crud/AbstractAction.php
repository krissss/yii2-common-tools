<?php

namespace kriss\actions\web\crud;

use kriss\components\MessageAlert;
use kriss\tools\Fun;
use kriss\traits\WebControllerTrait;

abstract class AbstractAction extends \kriss\actions\rest\crud\AbstractAction
{
    use WebControllerTrait;

    /**
     * @var string
     */
    public $operateMsg = '操作';
    /**
     * 是否显示 flash 消息
     * @var bool
     */
    public $setFlashMsg = true;

    /**
     * @inheritdoc
     */
    protected function doMethodOrCallback($method, $class, ...$parameter)
    {
        $result = parent::doMethodOrCallback($method, $class, $parameter);
        if ($this->setFlashMsg) {
            $this->messageAlert($result, $class);
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function findModel($id, $throwException = true)
    {
        return parent::findModel($id, $throwException);
    }

    /**
     * @param $result
     * @param $model
     */
    protected function messageAlert($result, $model)
    {
        if ($result !== false) {
            MessageAlert::success($this->operateMsg . '成功');
        } else {
            MessageAlert::error($this->operateMsg . '失败：' . Fun::formatModelErrors2String($model->errors));
        }
    }

    /**
     * 跳转到前一个页面
     */
    protected function redirectPrevious()
    {
        return $this->actionPreviousRedirect($this->controller);
    }
}
