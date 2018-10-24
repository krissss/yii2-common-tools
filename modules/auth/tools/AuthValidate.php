<?php

namespace kriss\modules\auth\tools;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * 验证用户是否具有某项权限
 */
class AuthValidate
{
    /**
     * 执行验证，如果不存在直接返回无权访问
     * 主要用于 action 中
     * @param $name string|array
     * @param bool $allContain // 是否要同时满足所有权限才能通过验证，否则只要具有一个权限即可通过
     * @throws ForbiddenHttpException
     */
    public static function run($name, $allContain = false)
    {
        if (!self::has($name, $allContain)) {
            throw new ForbiddenHttpException(Yii::t('kriss', '没有访问权限'));
        }
    }

    /**
     * 判断用户是否具有某权限
     * 主要用户 view 界面中
     * @param $name string|array
     * @param bool $allContain // 是否要同时满足所有权限才能通过验证，否则只要具有一个权限即可通过
     * @return bool
     */
    public static function has($name, $allContain = false)
    {
        $name = (array)$name;
        $number = count($name);
        $trueCount = 0;
        foreach ($name as $operation) {
            if (Yii::$app->user->can($operation)) {
                if (!$allContain) { // 非全部满足时只要一个满足就可以了
                    return true;
                }
                $trueCount++;
            }
        }
        if ($allContain) {
            if ($trueCount === $number) { // 全部满足时需要验证通过全部
                return true;
            }
        } else {
            if ($trueCount >= 1) { // 非全部满足时只需要验证通过的数量大于等于一
                return true;
            }
        }
        return false;
    }
}
