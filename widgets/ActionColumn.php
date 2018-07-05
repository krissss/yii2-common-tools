<?php

namespace kriss\widgets;

use yii\helpers\Html;

class ActionColumn extends \kartik\grid\ActionColumn
{
    public $header = '操作';

    public $width = null;

    public $template = '';

    /**
     * 二维或三维数组
     * 三维数组会自动合并分行
     * 每个按钮可配置的字段说明
     * action: 路由，必填
     * label: 显示的文本，默认使用 action
     * type: default、primary、info、warning、danger，默认 default
     * cssClass： 按钮的 class，默认无，在传递比如像 simple_ajax_form 时比较有用
     * options： 按钮的其他样式属性，默认无
     * visible： 可见性，默认 true，可以是 匿名函数 或 bool
     * @see renderButton
     * @var array
     */
    public $groupButtons = [];
    /**
     * 是否将按钮 group，在按钮很多时有用，配合 isGroupWrap 使用
     * @see renderButtonGroup
     * @var bool
     */
    public $isGroupButton = false;
    /**
     * 是否自动分行
     * @see
     * @var bool
     */
    public $isGroupWrap = false;

    protected function renderDataCellContent($model, $key, $index)
    {
        $buttons = [];
        foreach ($this->groupButtons as $button) {
            if (!isset($button['action'])) {
                $singleButtons = [];
                foreach ($button as $singleButton) {
                    $singleButtons[] = $this->renderButton($singleButton, $model, $key, $index);
                }
                $buttons[] = $this->renderButtonGroup($singleButtons);
            } else {
                $buttons[] = $this->renderButton($button, $model, $key, $index);
            }
        }
        if ($this->isGroupWrap) {
            $html = implode('<div style="height: 5px"></div>', $buttons);
        } else {
            $html = implode(' ', $buttons);
        }

        $templateValue = parent::renderDataCellContent($model, $key, $index);

        return $templateValue . $html;
    }

    protected function renderButton($button, $model, $key, $index)
    {
        $isVisible = true;
        if (isset($button['visible'])) {
            $isVisible = $button['visible'] instanceof \Closure
                ? call_user_func($button['visible'], $model, $key, $index)
                : $button['visible'];
        }
        if (!$isVisible) {
            return '';
        }

        $label = isset($button['label']) ? $button['label'] : $button['action'];
        $options = ['class' => 'btn btn-' . (isset($button['type']) ? $button['type'] : 'default')];
        if (isset($button['cssClass'])) {
            Html::addCssClass($options, $button['cssClass']);
        }
        if (isset($button['options'])) {
            $options = array_merge($options, $button['options']);
        }
        return Html::a($label, $this->createUrl($button['action'], $model, $key, $index), $options);
    }

    protected function renderButtonGroup($buttons)
    {
        $buttons = array_filter($buttons);
        if ($buttons) {
            if ($this->isGroupButton) {
                return Html::tag('div', implode("\n", $buttons), ['class' => 'btn-group']);
            } else {
                return implode(' ', $buttons);
            }
        }
        return null;
    }
}
