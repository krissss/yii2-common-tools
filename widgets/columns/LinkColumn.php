<?php

namespace kriss\widgets\columns;

use yii\base\InvalidConfigException;
use yii\helpers\Html;

/**
 * [
 *   'class' => LinkColumn::class,
 *   'label' => '所属门店',
 *   'attribute' => 'store.name',
 *   'linkUrl' => function (Person $model) {
 *      return ['store/detail', 'id' => $model->store_id];
 *   },
 *   'linkOptions' => ['class' => 'show_ajax_modal'],
 * ],
 *
 * @since 2.1.3
 * @see DetailViewAction
 */
class LinkColumn extends DataColumn
{
    public $linkUrl;

    public $linkOptions = [];

    public function init()
    {
        parent::init();
        if (!$this->linkUrl) {
            throw new InvalidConfigException('must config linkUrl');
        }
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);

        $linkUrl = is_callable($this->linkUrl) ? call_user_func($this->linkUrl, $model, $key, $index, $this) : $this->linkUrl;

        return Html::a($value, $linkUrl, $this->linkOptions);
    }
}
