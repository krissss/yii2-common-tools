<?php

namespace kriss\widgets;

use kartik\form\ActiveForm;
use Yii;
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
    public $returnLabel;
    public $returnHref;
    public $returnOptions = ['class' => 'btn btn-default'];

    public $renderSubmit = true;
    public $submitLabel;
    public $submitOptions = ['class' => 'btn btn-primary'];

    public $btnContainerOptions = [];

    public function init()
    {
        if (!isset($this->returnLabel)) {
            $this->returnLabel = Yii::t('kriss', '返回');
        }
        if (!isset($this->submitLabel)) {
            $this->submitLabel = Yii::t('kriss', '提交');
        }

        if (!isset($this->returnHref)) {
            $this->returnHref = 'javascript:history.back()';
        }
        parent::init();
    }

    public function run()
    {
        $header = $this->renderHeader();
        $content = ob_get_clean();
        $footer = $this->renderFooter();

        $beginForm = Html::beginForm($this->action, $this->method, $this->options);
        if ($this->enableClientScript) {
            $this->registerClientScript();
        }
        $endFrom = Html::endForm();

        $html = <<<HTML
<div class="box box-default">
    {$beginForm}
    {$header}
    <div class="box-body">
        {$content}
        {$footer}
    </div>
    {$endFrom}
</div>
HTML;
        return $html;
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
