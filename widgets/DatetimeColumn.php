<?php

namespace kriss\widgets;

class DatetimeColumn extends DataColumn
{
    public $dateFormat = 'Y-m-d H:i:s';

    public function init()
    {
        $this->format = ['datetime', 'php:' . $this->dateFormat];
        parent::init();
    }
}
