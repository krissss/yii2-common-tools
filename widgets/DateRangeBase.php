<?php

namespace kriss\widgets;

use kartik\field\FieldRange;
use Yii;
use yii\helpers\Html;

class DateRangeBase extends FieldRange
{
    const DISPLAY_TYPE_DATE = DateControl::FORMAT_DATE;
    const DISPLAY_TYPE_DATE_TIME = DateControl::FORMAT_DATETIME;
    const DISPLAY_TYPE_TIME = DateControl::FORMAT_TIME;

    /**
     * display type
     * @var string
     */
    public $displayType = self::DISPLAY_TYPE_DATE_TIME;
    /**
     * input type
     * @var string
     */
    public $type = self::INPUT_WIDGET;
    /**
     * label
     * @var string
     */
    public $label;
    /**
     * 日期之间的分隔符
     * @var string
     */
    public $separator = '-';
    /**
     * 消除与底部的距离
     * @var bool
     */
    public $noMarginButton = true;

    public function init()
    {
        if (!isset($this->label)) {
            $this->label = Yii::t('kriss', '时间区间');
        }

        if (!$this->widgetClass) {
            $this->widgetClass = DateControl::class;
            $this->widgetOptions1['type'] = $this->displayType;
            $this->widgetOptions2['type'] = $this->displayType;
        }
        if ($this->noMarginButton) {
            Html::addCssClass($this->container, 'no-margin-bottom');
            $this->getView()->registerCss('.no-margin-bottom{margin-bottom:0 !important}');
        }
        parent::init();
    }
}
