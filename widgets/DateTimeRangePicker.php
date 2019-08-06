<?php

namespace kriss\widgets;

use kartik\daterange\DateRangePicker;

/**
 * depends:
 * kartik-v/yii2-date-range
 *
 * in view
 * echo $form->field($model, 'created_at')->widget(DateTimeRangePicker::class);
 *
 * in searchForm
 * $timeRangeAttributes = ['created_at', 'pay_at', 'notify_at'];
 * foreach ($timeRangeAttributes as $attribute) {
 *     if ($this->$attribute) {
 *         list($start, $end) = explode(' - ', $this->$attribute);
 *         $query->andWhere(['between', $attribute, strtotime($start), strtotime($end)]);
 *     }
 * }
 */
class DateTimeRangePicker extends DateRangePicker
{
    public $format = 'Y-m-d H:i';

    public function init()
    {
        $this->convertFormat = true;
        $this->pluginOptions = array_merge([
            'timePicker' => true,
            'timePickerIncrement' => 1,
            'locale' => ['format' => $this->format]
        ], $this->pluginOptions);
        parent::init();
    }
}
