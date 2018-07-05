<?php

namespace kriss\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;

class ToggleColumn extends DataColumn
{
    /**
     * url
     * @see \yii\grid\ActionColumn
     */
    public $urlCreator;
    public $controller;
    public $action;

    // toggle data
    public $items = [
        1 => '是',
        0 => '否',
    ];
    public $onValue = 1;
    public $offValue = 0;

    // confirm
    public $onConfirm;
    public $offConfirm;

    // template
    public $templateWrapOptions = [
        'tag' => 'button',
        'class' => 'btn btn-primary btn-sm',
        'title' => '点击切换'
    ];
    public $templateWrapOptionsCannotOperate = [
        'tag' => 'span',
    ];
    public $templateOn = '<i class="glyphicon glyphicon-ok-circle"></i> {label}';
    public $templateOff = '<i class="glyphicon glyphicon-remove-circle"></i> {label}';
    public $templateLoading = '<i class="glyphicon glyphicon-refresh"></i>';

    /**
     * 是否可以操作
     * @var bool|callable
     */
    public $canOperate = true;

    /**
     * @var string
     */
    public $csrfParam = '_csrf-backend';

    private $_templateOnStr;
    private $_templateOffStr;
    private $_triggerClass;

    public function init()
    {
        parent::init();
        if (!$this->action) {
            throw new InvalidConfigException('action 必须配置');
        }
        $this->_triggerClass = 'toggle-column-' . $this->attribute;
        $this->generateOnOffLink();
        $this->initFilter();
        $this->registerJs();
    }

    public function initFilter()
    {
        if (!$this->filter) {
            $this->filter = $this->items;
        }
    }

    /**
     * @see \yii\grid\ActionColumn
     * @inheritdoc
     */
    public function createUrl($action, $model, $key, $index)
    {
        if (is_callable($this->urlCreator)) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index, $this);
        }

        $params = is_array($key) ? $key : ['id' => (string)$key];
        $params[0] = $this->controller ? $this->controller . '/' . $action : $action;

        return Url::toRoute($params);
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        $canOperate = $this->canOperate instanceof \Closure
            ? call_user_func($this->canOperate, $model, $key, $index)
            : $this->canOperate;

        $value = parent::renderDataCellContent($model, $key, $index);
        if ($canOperate) {
            $url = $this->createUrl($this->action, $model, $key, $index);
            $options = array_merge($this->templateWrapOptions, [
                'data-url' => $url,
                'data-value' => $value,
                'data-pjax' => '0'
            ]);
            Html::addCssClass($options, $this->_triggerClass);
        } else {
            $options = $this->templateWrapOptionsCannotOperate;
        }
        return Html::tag(
            isset($options['tag']) ? $options['tag'] : 'button',
            $value == $this->onValue ? $this->_templateOnStr : $this->_templateOffStr,
            $options
        );
    }

    protected function registerJs()
    {
        $js = <<<JS
$('body').on('click', '.{$this->_triggerClass}', function(e) {
    var loading = '{$this->templateLoading}',
        onConfirm = '{$this->onConfirm}',
        offConfirm = '{$this->offConfirm}',
        onLink = '{$this->_templateOnStr}',
        offLink = '{$this->_templateOffStr}',
        onValue = '{$this->onValue}',
        offValue = '{$this->offValue}',
        _this = $(this),
        currentValue = _this.attr('data-value'),
        canRequest = true;
    if (currentValue == onValue && offConfirm) {
        canRequest = confirm(offConfirm);
    } else if (currentValue == offValue && onConfirm) {
        canRequest = confirm(onConfirm);
    }
    if (!canRequest) {
        return;
    }
    var url = _this.attr('data-url');
    if (!url) {
        alert('no action');
        return;
    }
    _this.html(loading);
    $.post(url, {'{$this->csrfParam}': $('meta[name="csrf-token"]').attr("content")}, function(data) {
        _this.attr('data-value', data);
        _this.html(data == onValue ? onLink : (data == offValue ? offLink : '返回结果未知'));
    });
});
JS;
        $this->grid->view->registerJs($js);
    }

    protected function generateOnOffLink()
    {
        $this->_templateOnStr = strtr($this->templateOn, [
            '{label}' => isset($this->items[$this->onValue]) ? $this->items[$this->onValue] : '未知',
        ]);
        $this->_templateOffStr = strtr($this->templateOff, [
            '{label}' => isset($this->items[$this->offValue]) ? $this->items[$this->offValue] : '未知',
        ]);
    }
}