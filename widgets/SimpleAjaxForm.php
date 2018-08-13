<?php

namespace kriss\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

class SimpleAjaxForm extends ActiveForm
{
    /**
     * @var string
     */
    public $header;
    /**
     * lg sm
     * @var string
     */
    public $modalSize;

    public $renderCancel = true;
    public $cancelLabel = '取消';
    public $cancelOptions = ['class' => 'btn btn-default'];

    public $renderSubmit = true;
    public $submitLabel = '确定';
    public $submitOptions = ['class' => 'btn btn-primary'];

    public $options = ['class' => 'form-horizontal'];

    public $fieldConfig = [
        'template' => '{label}<div class="col-sm-10">{input}{hint}</div>{error}',
        'labelOptions' => ['class' => 'control-label col-sm-2'],
        'errorOptions' => ['class' => 'help-block col-sm-10 col-sm-offset-2'],
    ];

    public function init()
    {
        if (!isset($this->options['id'])) {
            // 解决 ajax 表单不能验证的问题
            $this->options['id'] = Widget::$autoIdPrefix . time();
        }
        parent::init();

        $modalSize = $this->modalSize ? ('modal-' . $this->modalSize) : '';
        echo Html::beginTag('div', ['class' => 'modal fade ajax_modal']);
        echo Html::beginTag('div', ['class' => "modal-dialog {$modalSize}"]);
        echo Html::beginTag('div', ['class' => 'modal-content']);
        ob_flush(); // 将上方的结构输出，form 表单从这个地方开始
        echo $this->renderHeader();
        echo Html::beginTag('div', ['class' => 'modal-body']);
    }

    public function run()
    {
        // form 表单囊括 header body footer
        echo Html::endTag('div'); // modal-body
        echo $this->renderFooter();

        parent::run();

        echo Html::endTag('div'); // modal-content
        echo Html::endTag('div'); // modal-dialog
        echo Html::endTag('div'); // modal
    }

    protected function renderHeader()
    {
        $header = '';
        if ($this->header || $this->renderCancel) {
            $cancelButton = $this->renderCancel ? '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' : '';
            $headerTitle = $this->header ? Html::tag('h4', $this->header, ['class' => 'model-title']) : '';
            $header = Html::tag('div', $cancelButton . $headerTitle, ['class' => 'modal-header']);
        }
        return $header;
    }

    protected function renderFooter()
    {
        $buttons = [];
        if ($this->renderCancel) {
            $this->cancelOptions['data-dismiss'] = 'modal';
            $buttons[] = Html::button($this->cancelLabel, $this->cancelOptions);
        }
        if ($this->renderSubmit) {
            $buttons[] = Html::submitButton($this->submitLabel, $this->submitOptions);
        }
        $footerButton = implode(' ', $buttons);
        return Html::tag('div', $footerButton, ['class' => 'modal-footer']);
    }
}
