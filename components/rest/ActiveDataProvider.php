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
            'class' => Pagination::className()
        ], $value);
        parent::setPagination($value);
    }

    /**
     * @inheritdoc
     */
    public function setSort($value)
    {
        $value = array_merge([
            'class' => Sort::className()
        ], $value);
        parent::setSort($value);
    }
}