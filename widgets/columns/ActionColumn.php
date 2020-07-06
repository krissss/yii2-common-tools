<?php

namespace kriss\widgets\columns;

use kriss\traits\KrissTranslationTrait;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class ActionColumn extends \kartik\grid\ActionColumn
{
    use KrissTranslationTrait;

    public $header;

    public $width = 'auto';

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
     * url: 按钮的点击链接地址，默认使用 action 的地址，会经过 Url::to() 处理，可以是 匿名函数 或 array，在直接跳转相关页面时比较有用
     * value: 默认无，匿名函数，无值时使用 button 按钮样式，在 value 特别复杂时比较有用
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
     * @var bool
     */
    public $isGroupWrap = false;
    /**
     * 是否通过下拉菜单的方式分组
     * @var bool
     */
    public $isGroupByDropDown = false;

    public function init()
    {
        $this->initKrissI18N();
        if (!isset($this->header)) {
            $this->header = Yii::t('kriss', '操作');
        }

        parent::init();
    }

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
            $html = implode('<div style="height: 5px"></div>', array_filter($buttons));
        } else {
            $html = implode(' ', array_filter($buttons));
        }

        $templateValue = parent::renderDataCellContent($model, $key, $index);

        return $templateValue . $html;
    }

    protected function renderButton($button, $model, $key, $index, $defaultVisible = true)
    {
        // visible
        $isVisible = $defaultVisible;
        if (isset($button['visible'])) {
            $isVisible = $button['visible'] instanceof \Closure
                ? call_user_func($button['visible'], $model, $key, $index)
                : $button['visible'];
        }
        if (!$isVisible) {
            return '';
        }

        // url
        if (!isset($button['url'])) {
            $url = $this->createUrl($button['action'], $model, $key, $index);
        } else {
            if ($button['url'] instanceof \Closure) {
                $url = call_user_func($button['url'], $model, $key, $index);
            } else {
                $url = $button['url'];
            }
            $url = Url::to($url);
        }

        // value
        if (isset($button['value']) && $button['value'] instanceof \Closure) {
            return call_user_func($button['value'], $url, $model, $key, $index);
        }

        // label
        $label = isset($button['label']) ? $button['label'] : $button['action'];

        // type
        $options = ['class' => 'btn btn-' . (isset($button['type']) ? $button['type'] : 'default')];

        // cssClass
        if (isset($button['cssClass'])) {
            Html::addCssClass($options, $button['cssClass']);
        }

        // options
        if (isset($button['options'])) {
            $options = array_merge($options, $button['options']);
        }

        return Html::a($label, $url, $options);
    }

    protected function renderButtonGroup($buttons)
    {
        // 去除空值和键值
        $buttons = array_values(array_filter($buttons));
        if ($buttons) {
            if ($this->isGroupButton) {
                if (count($buttons) > 1 && $this->isGroupByDropDown) {
                    $html = [];
                    $html[] = Html::beginTag('div', ['class' => 'btn-group']);
                    $html[] = $buttons[0];
                    preg_match('/btn btn-(\w*)/i', $buttons[0], $matches);
                    unset($buttons[0]);
                    $certType = $matches ? $matches[1] : 'default';
                    $html[] = Html::button('<span class="fa fa-caret-down"></span>', ['data-toggle' => 'dropdown', 'class' => 'btn btn-' . $certType]);
                    $html[] = Html::beginTag('ul', ['class' => 'dropdown-menu']);
                    foreach ($buttons as $button) {
                        $html[] = Html::tag('li', preg_replace('/(btn btn-(\w*))/i', '', $button));
                    }
                    $html[] = Html::endTag('ul'); //  ul.dropdown-menu
                    $html[] = Html::endTag('div'); //  div.btn-group
                    return implode("\n", $html);
                } else {
                    return Html::tag('div', implode("\n", $buttons), ['class' => 'btn-group']);
                }
            } else {
                return implode(' ', $buttons);
            }
        }
        return null;
    }
}
