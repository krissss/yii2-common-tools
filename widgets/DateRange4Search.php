<?php

namespace kriss\widgets;

class DateRange4Search extends DateRangeBase
{
    public $noMarginButton = true;

    public $template = <<<HTML
<div class="col-sm-12 col-md-12">
    {label}
    {widget}
    {error}
</div>
HTML;

    public $labelOptions = ['class' => 'control-label col-md-2'];

    public $widgetContainer = ['class' => 'col-md-10'];

    public $errorContainer = ['class' => 'help-block col-md-offset-2 col-md-10'];

    public $container = ['class' => 'col-sm-12 col-md-6'];
}