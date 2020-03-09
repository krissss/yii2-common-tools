<?php

namespace common\forms;

use common\widgets\SortableInputWidget;
use yii\base\Model;

abstract class SortableForm extends Model
{
    public $sorted;

    public function rules()
    {
        return [
            ['sorted', 'required'],
            ['sorted', 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'sorted' => '排序',
        ];
    }

    /**
     * @see SortableInputWidget::getSortableItems()
     * @return string|callable
     */
    abstract public function getSortContentAttribute();

    /**
     * @see SortableInputWidget::getSortableItems()
     * @return array|Model[]
     */
    abstract public function getSortItemModels();

    /**
     * @return array
     */
    protected function getSortedData()
    {
        return explode(',', $this->sorted);
    }
}
