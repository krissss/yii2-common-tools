<?php

namespace kriss\actions\traits;

use kriss\components\MessageAlert;
use kriss\tools\Fun;

trait FlashMessageTrait
{
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
     * @param $result
     * @param $model
     */
    protected function setFlashMessage($result, $model)
    {
        if ($this->setFlashMsg) {
            if ($result !== false) {
                MessageAlert::success($this->operateMsg . '成功');
            } else {
                MessageAlert::error($this->operateMsg . '失败：' . Fun::formatModelErrors2String($model->errors));
            }
        }
    }
}
