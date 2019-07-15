<?php

namespace kriss\widgets;

use app\kriss\widgets\BaseViewWidget;
use Yii;
use yii\helpers\Html;

class SimpleBoxView extends BaseViewWidget
{
    /**
     * @var string
     */
    public $header;

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
        $content = parent::run();
        $footerButton = $this->renderFooter();
        $html = <<<HTML
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">{$this->header}</h3>
    </div>
    <div class="box-body">
    {$content}
    </div>
    <div class="box-footer">
        {$footerButton}
    </div>
</div>
HTML;
        return $html;
    }

    protected function renderFooter()
    {
        $buttons = [];
        if ($this->renderCancel) {
            $buttons[] = Html::a($this->cancelLabel, 'javascript:window.history.back();', $this->cancelOptions);
        }
        return implode(' ', $buttons);
    }
}
