<?php

namespace kriss\widgets;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

class SimpleAjaxForm extends ActiveForm
{
    public $header;

    public $renderCancel = true;
    public $cancelLabel = '取消';
    public $cancelOptions = ['class' => 'btn btn-default'];

    public $renderSubmit = true;
    public $submitLabel = '确定';
    public $submitOptions = ['class' => 'btn btn-primary'];

    public function init()
    {
        $this->options = [
            'class' => 'form-horizontal'
        ];
        $this->fieldConfig = [
            'template' => '{label}<div class="col-sm-10">{input}</div>{error}',
            'labelOptions' => ['class' => 'control-label col-sm-2'],
            'errorOptions' => ['class' => 'help-block col-sm-10 col-sm-offset-2']
        ];
        parent::init();
    }

    public static function begin($config = [])
    {
        /** @var self $widget */
        $widget = parent::begin($config);
        echo <<<HTML
        <div class="modal fade ajax_modal">
    <div class="modal-dialog">
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
        if ($widget->renderSubmit) {
            $buttons[] = Html::submitButton($widget->submitLabel, $widget->submitOptions);
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