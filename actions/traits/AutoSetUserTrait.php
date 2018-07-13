<?php

namespace kriss\actions\traits;

use kriss\actions\helper\ActionTools;
use Yii;

/**
 * @property bool $autoSetUserId 是否自动设置用户变量为 Yii::$app->user->id，默认 false
 * @property string $autoSetUserIdAttribute 自动设置用户 id 的属性名，默认为 userId
 */
trait AutoSetUserTrait
{
    /**
     * @param $class
     * @return void
     */
    public function autoMergeUserId(&$class)
    {
        $autoSetUserId = isset($this->autoSetUserId) ? $this->autoSetUserId : false;
        $autoSetUserIdAttribute = isset($this->autoSetUserIdAttribute) ? $this->autoSetUserIdAttribute : 'userId';

        if ($autoSetUserId) {
            ActionTools::generateYiiObjectConfig($class, [$autoSetUserIdAttribute => Yii::$app->user->id]);
        }
    }
}
