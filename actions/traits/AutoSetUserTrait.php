<?php

namespace kriss\actions\traits;

use kriss\actions\helper\ActionTools;
use Yii;

trait AutoSetUserTrait
{
    /**
     * @var bool
     */
    public $autoSetUserId = false;
    /**
     * @var string
     */
    public $autoSetUserIdAttribute = 'userId';

    /**
     * @param $class
     * @return void
     */
    public function autoMergeUserId(&$class)
    {
        if ($this->autoSetUserId) {
            ActionTools::generateYiiObjectConfig($class, [$this->autoSetUserIdAttribute => Yii::$app->user->id]);
        }
    }
}
