<?php

namespace kriss\widgets;

use kartik\form\ActiveForm;
use yii\helpers\Html;

class SimpleSearchForm extends ActiveForm
{
    const TYPE_ONE = 'horizontal_label_1';

    public $layoutType;
    public $method = 'get';

    public $renderReset = true;
    public $restLabel = '重置';
    public $restOptions = ['class' => 'btn btn-default'];
    /**
     * 默认为 action 地址
     * @var array
     */
    public $restUrl;

    public $renderSubmit = true;
    public $submitLabel = '查询';
    public $submitOptions = ['class' => 'btn btn-primary'];

    public $btnContainerOptions = [];

    /**
     * 是否 折叠 widget
     * @var bool
     */
    public $isCollapsed = false;

    public function init()
    {
        if (!isset($this->layoutType)) {
            $this->layoutType = self::TYPE_ONE;
        }
        $this->options = [
            'class' => 'form-horizontal form-col-compact',
        ];
        if ($this->layoutType == self::TYPE_ONE) {
            $this->fieldConfig = [
                'template' => '{label}<div class="col-md-9">{input}</div>{error}',
                'options' => ['class' => 'col-sm-12 col-md-3'],
                'labelOptions' => ['class' => 'control-label col-md-3'],
                'errorOptions' => ['class' => 'help-block col-md-offset-3 col-md-9'],
            ];
        }
        parent::init();
    }

    /**
     * 生成表单提交按钮
     * @return string
     */
    public function renderFooterButtons()
    {
        if ($this->renderReset) {
            $btnReset = Html::a($this->restLabel, $this->restUrl ?: $this->action, $this->restOptions);
        }
        if ($this->renderSubmit) {
            $btnSubmit = Html::submitButton($this->submitLabel, $this->submitOptions);
        }

        $options = [
            'class' => 'col-md-offset-1',
        ];

        $options = array_merge($this->btnContainerOptions, $options);
        return Html::tag(
            'div',
            Html::tag(
                'div',
                (isset($btnReset) ? $btnReset : '') . ' ' .
                (isset($btnSubmit) ? $btnSubmit : ''),
                isset($options) ? $options : ''
            ),
            ['class' => 'col-sm-10']
        );
    }

    public static function begin($config = [])
    {
        /** @var self $widget */
        $widget = parent::begin($config);
        $collapsedClass = '';
        $collapsedToolsClass = 'fa-minus';
        if ($widget->isCollapsed === true) {
            $collapsedClass = 'collapsed-box';
            $collapsedToolsClass = 'fa-plus';
        }
        echo <<<HTML
        <div class="box box-default $collapsedClass">
    <div class="box-header with-border">
        <h3 class="box-title">查询</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa $collapsedToolsClass"></i>
            </button>
        </div>
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
