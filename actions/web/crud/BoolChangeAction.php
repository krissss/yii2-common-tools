<?php

namespace kriss\actions\web\crud;

use yii\base\InvalidConfigException;
use yii\base\Model;

class BoolChangeAction extends AbstractAction
{
    /**
     * @var string
     */
    public $attribute;
    /**
     * @var string|callable
     */
    public $changeMethod = 'save';
    /**
     * 是否强转为 int 结果
     * @var bool
     */
    public $forceToInt = true;

    public function init()
    {
        parent::init();
        if (!$this->attribute) {
            throw new InvalidConfigException('必须配置 attribute');
        }
    }

    public function run($id)
    {
        /** @var Model $model */
        $model = $this->findModel($id);
        $attribute = $this->attribute;

        $model->$attribute = !$model->$attribute;
        $this->forceToInt && $model->$attribute = (int)$model->$attribute;
        $result = $this->doMethodOrCallback($this->changeMethod, $model);
        $this->messageAlert($result, $model);

        return $this->redirectPrevious();
    }
}
