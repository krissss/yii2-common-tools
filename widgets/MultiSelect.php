<?php

namespace kriss\widgets;

use kartik\select2\Select2;

/**
 * depends:
 * kartik-v/yii2-widget-select2
 *
 * in view
 * echo $form->field($model, 'status')->widget(MultiSelect::class, [
 *    'data' => OrderStatus::getViewItems(),
 * ]);
 *
 * in searchForm
 * rules:
 * [['status', 'notify_status'], 'each', 'rule' => ['integer']],
 */
class MultiSelect extends Select2
{
    public function init()
    {
        $this->options['multiple'] = true;
        $this->theme = 'default';
        parent::init();
    }
}
