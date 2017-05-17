<?php

namespace kriss\widgets;

use kartik\field\FieldRange;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;

class DateRangeBase extends FieldRange
{
    const DISPLAY_TYPE_DATE = DateControl::FORMAT_DATE;
    const DISPLAY_TYPE_DATE_TIME = DateControl::FORMAT_DATETIME;
    const DISPLAY_TYPE_TIME = DateControl::FORMAT_TIME;

    public $displayType = self::DISPLAY_TYPE_DATE_TIME;

    public $type = self::INPUT_WIDGET;

    public $label = '时间区间';

    public $separator = '-';

    public function init()
    {
        if (!$this->widgetClass) {
            $this->widgetClass = DateControl::className();
            $this->widgetOptions1['type'] = $this->displayType;
            $this->widgetOptions2['type'] = $this->displayType;
        }
        parent::init();
    }

    public function renderWidget()
    {
        Html::addCssClass($this->options, 'kv-field-range');

        Html::addCssClass($this->options, 'input-group');
        $tag = ArrayHelper::remove($this->separatorOptions, 'tag', 'span');
        $widget = isset($this->form) ? $this->getFormInput() : $this->getInput(1) .
            Html::tag($tag, $this->separator, $this->separatorOptions) . $this->getInput(2);
        $widget = Html::tag('div', $widget, $this->options);

        $widget = Html::tag('div', $widget, $this->widgetContainer);
        $error = Html::tag('div', '<div class="help-block"></div>', $this->errorContainer);

        echo Html::tag('div', strtr($this->template, [
            '{label}' => Html::label($this->label, null, $this->labelOptions),
            '{widget}' => $widget,
            '{error}' => $error
        ]), $this->container);
    }
}