<?php

namespace kriss\actions\web\crud;

use kriss\actions\helper\ActionTools;
use kriss\actions\traits\ModelClassActionTrait;
use kriss\tools\Fun;
use yii\base\Action;
use yii\base\Exception;
use yii\base\InvalidConfigException;

class ToggleAction extends Action
{
    use ModelClassActionTrait;

    /**
     * @var string
     */
    public $attribute;
    /**
     * @deprecated
     * alias of doMethod
     * @var string|callable
     */
    public $changeMethod;
    /**
     * @var string|callable
     */
    public $doMethod = 'save';

    public $onValue = 1;
    public $offValue = 0;

    public function init()
    {
        parent::init();
        if (!$this->attribute) {
            throw new InvalidConfigException('必须配置 attribute');
        }
        if ($this->changeMethod) {
            $this->doMethod = $this->changeMethod;
        }
    }

    public function run($id)
    {
        $model = $this->findModel($id, $this->controller);
        $attribute = $this->attribute;
        $oldValue = $model->$attribute;

        if ($oldValue == $this->onValue) {
            $model->$attribute = $this->offValue;
        } elseif ($oldValue == $this->offValue) {
            $model->$attribute = $this->onValue;
        } else {
            throw new Exception("oldValue: {$oldValue} 不在 [{$this->onValue}, {$this->offValue}] 之间");
        }
        if ($this->doMethod == 'save') {
            // save 不校验数据
            $result = ActionTools::invokeClassMethod($model, $this->doMethod, false);
        } else {
            $result = ActionTools::invokeClassMethod($model, $this->doMethod);
        }
        if ($result === false) {
            throw new Exception('操作执行错误:' . Fun::formatModelErrors2String($model->errors));
        }

        return $model->$attribute;
    }
}
