<?php

namespace kriss\components\rest;

class ActiveDataProvider extends \yii\data\ActiveDataProvider
{
    public function init()
    {
        parent::init();
        $this->pagination = [
            'pageParam' => 'page',
            'pageSizeParam' => 'per_page',
            'validatePage' => false,
        ];
    }
}