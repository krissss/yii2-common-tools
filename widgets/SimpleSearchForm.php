<?php

namespace kriss\widgets;

use kartik\form\ActiveForm;
use kriss\traits\KrissTranslationTrait;
use Yii;
use yii\helpers\Html;

class SimpleSearchForm extends ActiveForm
{
    use KrissTranslationTrait;

    const TYPE_ONE = 'horizontal_label_1';

    public $layoutType;
    public $method = 'get';

    public $header;

    public $renderReset = true;
    public $restLabel;
    public $restOptions = ['class' => 'btn btn-default'];
    /**
     * 默认为 action 地址
     * @var array
     */
    public $restUrl;

    public $renderSubmit = true;
    public $submitLabel;
    public $submitOptions = ['class' => 'btn btn-primary'];

    public $btnContainerOptions = [];

    /**
     * 是否 折叠 widget
     * @var bool
     */
    public $isCollapsed = false;

    public function init()
    {
        $this->initKrissI18N();
        if (!isset($this->header)) {
            $this->header = Yii::t('kriss', '查询');
        }
        if (!isset($this->restLabel)) {
            $this->restLabel = Yii::t('kriss', '重置');
        }
        if (!isset($this->submitLabel)) {
            $this->submitLabel = Yii::t('kriss', '查询');
        }

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

        $collapsedClass = '';
        $collapsedToolsClass = 'fa-minus';
        if ($this->isCollapsed === true) {
            $collapsedClass = 'collapsed-box';
            $collapsedToolsClass = 'fa-plus';
        }
        echo Html::beginTag('div', ['class' => 'box box-default ' . $collapsedClass]);
        ob_flush();
        echo $this->renderHeader($collapsedToolsClass);
        echo Html::beginTag('div', ['class' => 'box-body']);
    }

    public function run()
    {
        echo $this->renderFooter();
        echo Html::endTag('div'); // box-body

        parent::run();

        echo Html::endTag('div'); // box
    }

    protected function renderHeader($collapsedToolsClass) {
        echo <<<HTML
<div class="box-header with-border">
    <h3 class="box-title">{$this->header}</h3>
    <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa $collapsedToolsClass"></i>
        </button>
    </div>
</div>
HTML;
    }

    protected function renderFooter() {
        $buttons = [];
        if ($this->renderReset) {
            $buttons[] = Html::a($this->restLabel, $this->restUrl ?: $this->action, $this->restOptions);
        }
        if ($this->renderSubmit) {
            $buttons[] = Html::submitButton($this->submitLabel, $this->submitOptions);
        }
        $footerButton = implode(' ', $buttons);
        $options = ['class' => 'col-md-offset-1'];
        $options = array_merge($this->btnContainerOptions, $options);
        return Html::tag('div', Html::tag('div', $footerButton, $options), ['class' => 'col-sm-10']);
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
