<?php

namespace kriss\actions\traits;

use kriss\components\MessageAlert;
use kriss\tools\Fun;
use Yii;

trait FlashMessageTrait
{
    /**
     * 是否显示 flash 消息
     * @var bool
     */
    public $setFlashMsg = true;
    /**
     * 操作提示，若为 null 则显示为操作
     * @var null|string
     */
    public $operateMsg = null;

    /**
     * @param $result
     * @param $model
     */
    protected function setFlashMessage($result, $model)
    {
        if ($this->setFlashMsg) {
            if ($this->operateMsg === null) {
                $this->operateMsg = Yii::t('kriss', '操作');
            }
            $operateMsg = $this->operateMsg;
            if ($result !== false) {
                MessageAlert::success($operateMsg . Yii::t('kriss', '成功'));
            } else {
                MessageAlert::error($operateMsg . Yii::t('kriss', '失败') . ': ' . Fun::formatModelErrors2String($model->errors));
            }
        }
    }
}
