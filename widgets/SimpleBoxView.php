<?php

namespace kriss\widgets;

use kriss\traits\KrissTranslationTrait;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class SimpleBoxView extends Widget
{
    use KrissTranslationTrait;

    /**
     * @var string
     */
    public $header;

    public $renderCancel = true;
    public $cancelLabel;
    public $cancelOptions = ['class' => 'btn btn-default'];

    public function init()
    {
        $this->initKrissI18N();
        if (!isset($this->header)) {
            $this->header = Yii::t('kriss', '详情');
        }
        if (!isset($this->cancelLabel)) {
            $this->cancelLabel = Yii::t('kriss', '取消');
        }

        parent::init();
    }

    public static function begin($config = [])
    {
        /** @var self $widget */
        $widget = parent::begin($config);
        echo <<<HTML
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">{$widget->header}</h3>
            </div>
            <div class="box-body">
HTML;
        return $widget;
    }

    public static function end()
    {
        /** @var self $widget */
        $widget = parent::end();
        $buttons = [];
        if ($widget->renderCancel) {
            $buttons[] = Html::a($widget->cancelLabel, 'javascript:window.history.back();', $widget->cancelOptions);
        }
        $footerButton = implode(' ', $buttons);
        echo <<<HTML
    </div>
    <div class="box-footer">
        {$footerButton}
    </div>
</div>
HTML;
        return $widget;
    }
}
