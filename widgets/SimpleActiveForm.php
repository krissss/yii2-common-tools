<?php

namespace kriss\widgets;

use kartik\form\ActiveForm;
use yii\helpers\Html;

class SimpleActiveForm extends ActiveForm
{
    public $options = [
        'class' => 'form-horizontal',
    ];
    public $fieldConfig = [
        'template' => '{label}<div class="col-sm-5">{input}{hint}</div>{error}',
        'labelOptions' => ['class' => 'control-label col-sm-2'],
        'errorOptions' => ['class' => 'help-block col-sm-5'],
    ];

    /**
     * @deprecated 请使用 header 代替
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

        echo Html::beginTag('div', ['class' => 'box box-default']);
        ob_flush();
        echo $this->renderHeader();
        echo Html::beginTag('div', ['class' => 'box-body']);
    }

    public function run()
    {
        echo $this->renderFooter();
        echo Html::endTag('div'); // box-body

        parent::run();

        echo Html::endTag('div'); // box
    }

    protected function renderHeader()
    {
        $title = $this->header ?: $this->title;
        if ($title) {
            return Html::tag('div', Html::tag('h3', $title, ['class' => 'box-title']), ['class' => 'box-header with-border']);
        }
        return null;
    }

    protected function renderFooter()
    {
        $buttons = [];
        if ($this->renderReturn) {
            $buttons[] = Html::a($this->returnLabel, $this->returnHref ?: 'javascript:window.history.back()', $this->returnOptions);
        }
        if ($this->renderSubmit) {
            $buttons[] = Html::submitButton($this->submitLabel, $this->submitOptions);
        }
        $footerButton = implode(' ', $buttons);
        $options = ['class' => 'col-sm-offset-2 col-sm-5'];
        $options = array_merge($this->btnContainerOptions, $options);
        return Html::tag('div', Html::tag('div', $footerButton, $options), ['class' => 'form-group']);
    }

    /**
     * 生成表单提交按钮
     * @deprecated 会自动调用
     * @return string
     */
    public function renderFooterButtons()
    {
        return null;
    }
}
