<?php

namespace kriss\widgets;

use Yii;
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
    public $cancelLabel;
    public $cancelOptions = ['class' => 'btn btn-default'];

    public $renderSubmit = true;
    public $submitLabel;
    public $submitOptions = ['class' => 'btn btn-primary'];

    public $options = ['class' => 'form-horizontal'];

    public $fieldConfig = [
        'template' => '{label}<div class="col-sm-10">{input}{hint}</div>{error}',
        'labelOptions' => ['class' => 'control-label col-sm-2'],
        'errorOptions' => ['class' => 'help-block col-sm-10 col-sm-offset-2'],
    ];

    public function init()
    {
        if (!isset($this->cancelLabel)) {
            $this->cancelLabel = Yii::t('kriss', '取消');
        }
        if (!isset($this->submitLabel)) {
            $this->submitLabel = Yii::t('kriss', '提交');
        }

        if (!isset($this->options['id'])) {
            // 解决 ajax 表单不能验证的问题
            $this->options['id'] = Widget::$autoIdPrefix . time();
        }
        parent::init();
    }

    public function run()
    {
        $modalSize = $this->modalSize ? ('modal-' . $this->modalSize) : '';
        $modalHeader = $this->renderHeader();
        $modalBody = parent::run();
        $modalFooter = $this->renderFooter();

        $html = <<<HTML
<div class="modal fade ajax_modal">
  <div class="modal-dialog {$modalSize}">
    <div class="modal-content">
      {$modalHeader}
      <div class="modal-body">
        {$modalBody}
      </div>
      {$modalFooter}
    </div>
  </div>
</div>
HTML;
        return $html;
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
