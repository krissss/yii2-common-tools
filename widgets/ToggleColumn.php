<?php

namespace kriss\widgets;

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

    // template
    public $templateWrap = '<a class="toggle-column btn btn-primary btn-sm" data-pjax="0" title="点击切换" href="{url}">{label}</a>';
    public $templateOn = '<i class="glyphicon glyphicon-ok-circle"></i> {label}';
    public $templateOff = '<i class="glyphicon glyphicon-remove-circle"></i> {label}';
    public $templateLoading = '<i class="glyphicon glyphicon-refresh"></i>';

    /**
     * @var string
     */
    public $csrfParam = '_csrf-backend';

    private $_templateOnStr;
    private $_templateOffStr;

    public function init()
    {
        parent::init();
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
        $url = $this->createUrl($this->action, $model, $key, $index);
        $value = parent::renderDataCellContent($model, $key, $index);
        return strtr($this->templateWrap, [
            '{url}' => $url,
            '{label}' => (bool)$value ? $this->_templateOnStr : $this->_templateOffStr
        ]);
    }

    protected function registerJs()
    {
        $js = <<<JS
$('body').on('click', 'a.toggle-column', function(e) {
    var loading = '{$this->templateLoading}',
        onLink = '{$this->_templateOnStr}',
        offLink = '{$this->_templateOffStr}',
        onValue = '{$this->onValue}',
        offValue = '{$this->offValue}',
        _this = $(this);
    e.preventDefault();
    var url = _this.attr('href');
    if (!url) {
        alert('no action');
        return;
    }
    _this.html(loading);
    $.post(url, {'{$this->csrfParam}': $('meta[name="csrf-token"]').attr("content")}, function(data) {
        _this.html(data == onValue ? onLink : (data == offValue ? offLink : '返回结果未知'));
    });
    return false;
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
