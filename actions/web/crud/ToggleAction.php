<?php

namespace kriss\actions\web\crud;

use kriss\actions\helper\ActionTools;
use kriss\actions\traits\ModelClassActionTrait;
use kriss\tools\Fun;
use yii\base\Exception;
use yii\base\InvalidConfigException;

class ToggleAction extends AbstractAction
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
    public $doMethod = 'update';

    public $onValue = 1;
    public $offValue = 0;

    /**
     * 当旧数据不等于 on 和 off 的值时，修改为什么值
     * -1 置为 on 的值，-2 置为 off 的值，-3 报错（切换的值必须在 on 和 off 之间），其他则修改为该值
     * @var int|string
     */
    public $whenOldValueNotMatchedCurrent = -1;

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
            if ($this->whenOldValueNotMatchedCurrent === -1) {
                $model->$attribute = $this->onValue;
            } elseif ($this->whenOldValueNotMatchedCurrent === -2) {
                $model->$attribute = $this->offValue;
            } elseif ($this->whenOldValueNotMatchedCurrent === -3) {
                throw new Exception("oldValue: {$oldValue} 不在 [{$this->onValue}, {$this->offValue}] 之间");
            } else {
                $model->$attribute = $this->whenOldValueNotMatchedCurrent;
            }
        }
        if ($this->doMethod == 'update') {
            // save 不校验数据
            $result = $model->update(false, [$this->attribute]);
        } elseif ($this->doMethod == 'save') {
            // save 不校验数据
            $result = $model->save(false);
        } else {
            $result = ActionTools::invokeClassMethod($model, $this->doMethod);
        }
        if ($result === false) {
            throw new Exception('操作执行错误:' . Fun::formatModelErrors2String($model->errors));
        }

        return $model->$attribute;
    }
}
