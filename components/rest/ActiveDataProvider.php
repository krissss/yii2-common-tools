<?php

namespace kriss\components\rest;

class ActiveDataProvider extends \yii\data\ActiveDataProvider
{
    /**
     * @inheritdoc
     */
    public function setPagination($value)
    {
        $value = array_merge([
            'class' => Pagination::class,
        ], $value);
        parent::setPagination($value);
    }

    /**
     * @inheritdoc
     */
    public function setSort($value)
    {
        $value = array_merge([
            'class' => Sort::class,
        ], $value);
        parent::setSort($value);
    }
}
