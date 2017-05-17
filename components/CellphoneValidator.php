<?php
/**
 * 手机号验证规则
 */
namespace kriss\components;

use yii\validators\Validator;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\helpers\Json;
use yii\validators\ValidationAsset;

class CellphoneValidator extends Validator
{
    public $pattern;
    public $not = false;

    public function init()
    {
        parent::init();
        $this->pattern = '/^1[0-9]{10}$/';
        $this->message = '手机号不合法';
    }

    protected function validateValue($value)
    {
        $valid = !is_array($value) &&
            (!$this->not && preg_match($this->pattern, $value)
                || $this->not && !preg_match($this->pattern, $value));

        return $valid ? null : [$this->message, []];
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $pattern = Html::escapeJsRegularExpression($this->pattern);

        $options = [
            'pattern' => new JsExpression($pattern),
            'not' => $this->not,
            'message' => $this->message
        ];
        if ($this->skipOnEmpty) {
            $options['skipOnEmpty'] = 1;
        }

        ValidationAsset::register($view);

        return 'yii.validation.regularExpression(value, messages, ' . Json::htmlEncode($options) . ');';
    }
}