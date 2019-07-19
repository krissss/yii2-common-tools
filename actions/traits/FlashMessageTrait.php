<?php

namespace kriss\actions\traits;

use kriss\actions\helper\ActionTools;
use kriss\components\MessageAlert;
use kriss\tools\Fun;
use Yii;

/**
 * @property bool $setFlashMsg 是否显示 flash 消息，默认为 true
 * @property string $operateMsg 操作提示，默认为 操作
 */
trait FlashMessageTrait
{
    /**
     * @param $result
     * @param $model
     */
    protected function setFlashMessage($result, $model)
    {
        $setFlashMsg = ActionTools::getTraitProperty($this, 'setFlashMsg', true);
        $operateMsg = ActionTools::getTraitProperty($this, 'operateMsg', Yii::t('kriss', '操作'));

        if ($setFlashMsg) {
            if ($result !== false) {
                MessageAlert::success($operateMsg . Yii::t('kriss', '成功'));
            } else {
                MessageAlert::error($operateMsg . Yii::t('kriss', '失败') . ': ' . Fun::formatModelErrors2String($model->errors));
            }
        }
    }
}
