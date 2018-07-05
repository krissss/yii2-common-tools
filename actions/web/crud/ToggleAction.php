<?php

namespace kriss\actions\web\crud;

use yii\base\Exception;
use yii\base\InvalidConfigException;

class ToggleAction extends AbstractAction
{
    /**
     * @var string
     */
    public $attribute;
    /**
     * @var string|callable
     */
    public $changeMethod = 'save';

    public $onValue = 1;
    public $offValue = 0;

    public function init()
    {
        parent::init();
        if (!$this->attribute) {
            throw new InvalidConfigException('必须配置 attribute');
        }
    }

    public function run($id)
    {
        $model = $this->findModel($id);
        $attribute = $this->attribute;
        $oldValue = $model->$attribute;

        $model->$attribute = $oldValue == $this->onValue ? $this->offValue : ($oldValue == $this->offValue ? $this->onValue : '未知');
        $result = $this->doMethodOrCallback($this->changeMethod, $model);
        if (!$result) {
            throw new Exception('操作执行错误');
        }

        return $model->$attribute;
    }
}
