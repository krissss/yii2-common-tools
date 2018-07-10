<?php

namespace kriss\actions\web\crud;

use kriss\components\MessageAlert;
use yii\base\InvalidConfigException;

class ChangeStatusAction extends AbstractAction
{
    /**
     * 需要改成的状态 => 当前的状态必须是其中一个
     * [
     *      Admin::STATUS_NORMAL => Admin::STATUS_DISABLED, // 只有 disabled 下的才能改为 normal
     *      Admin::STATUS_DISABLED => [Admin::STATUS_NORMAL, Admin::STATUS_LOCKED], // normal、locked 都能改为 disabled
     * ]
     * @var array
     */
    public $statusRelation;
    /**
     * @var string
     */
    public $statusAttribute = 'status';
    /**
     * @var string|callable
     */
    public $changeMethod = 'save';

    public function init()
    {
        parent::init();
        if (!$this->statusRelation) {
            throw new InvalidConfigException('必须配置 statusRelation');
        }
    }

    public function run($id, $status)
    {
        $model = $this->findModel($id);
        $currentStatus = $model->{$this->statusAttribute};
        if (in_array($currentStatus, (array)$this->statusRelation[$status])) {
            $model->{$this->statusAttribute} = $status;
            $this->doMethodOrCallback($this->changeMethod, $model, $model);
        } else {
            MessageAlert::error('当前状态下操作失败');
        }
        return $this->redirectPrevious();
    }
}
