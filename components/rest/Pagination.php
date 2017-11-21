<?php

namespace kriss\components\rest;

class Pagination extends \yii\data\Pagination
{
    /**
     * @var string
     */
    public $pageParam = 'page';
    /**
     * @var string
     */
    public $pageSizeParam = 'per_page';
    /**
     * @var bool
     */
    public $validatePage = false;
    /**
     * @var int
     */
    public $defaultPageSize = 10;
}