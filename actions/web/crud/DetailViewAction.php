<?php

namespace kriss\actions\web\crud;

use kriss\widgets\SimpleAjaxView;
use kriss\widgets\SimpleBoxView;
use yii\base\InvalidConfigException;
use yii\widgets\DetailView;

/**
 * $actions['detail'] = [
 *   'class' => DetailViewAction::class,
 *   'modelClass' => Store::class,
 *   'attributes' => ['id', 'name'],
 * ];
 *
 * @since 2.1.3
 * @see LinkColumn
 */
class DetailViewAction extends AbstractModelAction
{
    /**
     * attributes in DetailView
     * 不设置将显示全部
     * @see DetailView::$attributes
     * @var array
     */
    public $attributes = [];
    /**
     * 当传递参数 type 时，该参数起作用，attributes 将使用此处的配置
     * [[type => attributes]]
     * @var array
     */
    public $mapAttributes;
    /**
     * @var DetailView|string
     */
    public $detailViewClass = DetailView::class;
    /**
     * @see SimpleAjaxView
     * @see SimpleBoxView
     * @var array
     */
    public $wrapConfig = [];

    public function run($id, $type = null)
    {
        if ($type !== null) {
            if (isset($this->mapAttributes[$type])) {
                $attributes = $this->mapAttributes[$type];
            } else {
                throw new InvalidConfigException('mapAttributes has no index named: ' . $type);
            }
        } else {
            $attributes = $this->attributes;
        }
        $content = ($this->detailViewClass)::widget([
            'model' => $this->findModel($id, $this->controller),
            'attributes' => $attributes ?: null,
        ]);

        if ($this->isAjax) {
            SimpleAjaxView::begin($this->wrapConfig);
            echo $content;
            SimpleAjaxView::end();
        } else {
            SimpleBoxView::begin($this->wrapConfig);
            echo $content;
            SimpleBoxView::end();
        }
    }
}
