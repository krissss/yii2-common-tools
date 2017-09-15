<?php

namespace kriss\widgets;

use kartik\field\FieldRange;
use yii\helpers\Html;

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
        Html::addCssClass($this->container, 'no-margin-bottom');
        $this->getView()->registerCss('no-margin-bottom{margin-bottom:0 !important}');
        parent::init();
    }
}