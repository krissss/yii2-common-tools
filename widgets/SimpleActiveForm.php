<?php
/**
 * 简单的activeFrom
 * 包含配置等
 */

namespace kriss\widgets;

use yii\helpers\Html;
use kartik\form\ActiveForm;

class SimpleActiveForm extends ActiveForm
{
    public $options = [
        'class' => 'form-horizontal'
    ];
    public $fieldConfig = [
        'template' => '{label}<div class="col-sm-5">{input}{hint}</div>{error}',
        'labelOptions' => ['class' => 'control-label col-sm-2'],
        'errorOptions' => ['class' => 'help-block col-sm-5']
    ];

    /**
     * @var string
     */
    public $title;
    /**
     * 和 title 一样，若 header 有值，以 header 为主
     * @var string
     */
    public $header;

    public $renderReturn = true;
    public $returnLabel = '返回';
    public $returnHref;
    public $returnOptions = ['class' => 'btn btn-default'];

    public $renderSubmit = true;
    public $submitLabel = '确定';
    public $submitOptions = ['class' => 'btn btn-primary'];

    public $btnContainerOptions = [];

    public function init()
    {
        if (!isset($this->returnHref)) {
            $this->returnHref = 'javascript:history.back()';
        }
        parent::init();
    }

    /**
     * 生成表单提交按钮
     * @return string
     */
    public function renderFooterButtons()
    {
        if ($this->renderReturn) {
            $btnReturn = Html::a($this->returnLabel, $this->returnHref ?: 'javascript:window.history.back()', $this->returnOptions);
        }
        if ($this->renderSubmit) {
            $btnSubmit = Html::submitButton($this->submitLabel, $this->submitOptions);
        }
        $options = [
            'class' => 'col-sm-offset-2 col-sm-5'
        ];
        $options = array_merge($this->btnContainerOptions, $options);
        return Html::tag('div',
            Html::tag('div',
                (isset($btnReturn) ? $btnReturn : '') . ' ' .
                (isset($btnSubmit) ? $btnSubmit : ''),
                isset($options) ? $options : ''),
            ['class' => 'form-group']);
    }

    public static function begin($config = [])
    {
        /** @var self $widget */
        $widget = parent::begin($config);
        $title = $widget->header ?: $widget->title;
        echo <<<HTML
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">{$title}</h3>
            </div>
            <div class="box-body">
HTML;
        return $widget;
    }

    public static function end()
    {
        $widget = parent::end();
        echo <<<HTML
    </div>
</div>
HTML;
        return $widget;
    }
}
