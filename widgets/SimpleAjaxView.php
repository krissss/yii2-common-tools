<?php

namespace kriss\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class SimpleAjaxView extends Widget
{
    /**
     * @var string
     */
    public $header = '详情';
    /**
     * lg sm
     * @var string
     */
    public $modalSize;

    public $renderCancel = true;
    public $cancelLabel = '取消';
    public $cancelOptions = ['class' => 'btn btn-default'];

    public static function begin($config = [])
    {
        /** @var self $widget */
        $widget = parent::begin($config);
        $modalSize = $widget->modalSize ? ('modal-' . $widget->modalSize) : '';
        echo <<<HTML
        <div class="modal fade ajax_modal">
    <div class="modal-dialog {$modalSize}">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{$widget->header}</h4>
            </div>
            <div class="modal-body">
HTML;
        return $widget;
    }

    public static function end()
    {
        /** @var self $widget */
        $widget = parent::end();
        $buttons = [];
        if ($widget->renderCancel) {
            $widget->cancelOptions['data-dismiss'] = 'modal';
            $buttons[] = Html::button($widget->cancelLabel, $widget->cancelOptions);
        }
        $footerButton = implode(' ', $buttons);
        echo <<<HTML
    </div>
            <div class="modal-footer">
                {$footerButton}
            </div>
HTML;
        return $widget;
    }
}