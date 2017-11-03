<?php

namespace kriss\widgets;

use yii\widgets\ActiveForm;

class SimpleAjaxForm extends ActiveForm
{
    public $header;

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
        $widget = parent::end();
        echo <<<HTML
    </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary">确认</button>
            </div>
HTML;
        return $widget;
    }
}