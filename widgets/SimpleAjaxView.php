<?php

namespace kriss\widgets;

use app\kriss\widgets\BaseViewWidget;
use Yii;
use yii\helpers\Html;

class SimpleAjaxView extends BaseViewWidget
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

    public function init()
    {
        if (!isset($this->header)) {
            $this->header = Yii::t('kriss', '详情');
        }
        if (!isset($this->cancelLabel)) {
            $this->cancelLabel = Yii::t('kriss', '取消');
        }

        parent::init();
    }

    public function run()
    {
        $modalSize = $this->modalSize ? ('modal-' . $this->modalSize) : '';
        $content = parent::run();
        $footerButton = $this->renderFooter();
        $html = <<<HTML
<div class="modal fade ajax_modal">
    <div class="modal-dialog {$modalSize}">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{$this->header}</h4>
            </div>
            <div class="modal-body">
                {$content}
            </div>
            <div class="modal-footer">
                {$footerButton}
            </div>
        </div>
    </div>
</div>
HTML;
        return $html;
    }

    protected function renderFooter()
    {
        $buttons = [];
        if ($this->renderCancel) {
            $this->cancelOptions['data-dismiss'] = 'modal';
            $buttons[] = Html::button($this->cancelLabel, $this->cancelOptions);
        }
        return implode(' ', $buttons);
    }
}
