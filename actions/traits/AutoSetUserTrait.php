<?php

namespace kriss\actions\traits;

use kriss\actions\helper\ActionTools;
use Yii;

trait AutoSetUserTrait
{
    /**
     * 是否自动设置用户变量为 Yii::$app->user->id
     * @var bool
     */
    public $autoSetUserId = false;
    /**
     * 自动设置用户 id 的属性名
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
