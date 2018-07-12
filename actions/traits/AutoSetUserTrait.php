<?php

namespace kriss\actions\traits;

use Yii;

trait AutoSetUserTrait
{
    use ToolsTrait;

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
            $this->generateYiiObjectConfig($class, [$this->autoSetUserIdAttribute => Yii::$app->user->id]);
        }
    }
}
