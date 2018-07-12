<?php

namespace kriss\actions\web\crud;

use kriss\actions\helper\ActionTools;
use kriss\actions\traits\FlashMessageTrait;
use kriss\actions\traits\ModelClassActionTrait;
use yii\base\InvalidConfigException;

class BoolChangeAction extends AbstractAction
{
    use ModelClassActionTrait;
    use FlashMessageTrait;

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
        $model = $this->findModel($id, $this->controller);
        $attribute = $this->attribute;

        $model->$attribute = !$model->$attribute;
        $this->forceToInt && $model->$attribute = (int)$model->$attribute;
        if ($this->changeMethod == 'save') {
            // save 不校验数据
            $result = ActionTools::invokeClassMethod($model, $this->changeMethod, false);
        } else {
            $result = ActionTools::invokeClassMethod($model, $this->changeMethod);
        }
        $this->setFlashMessage($result, $model);

        return $this->redirectPrevious();
    }
}
