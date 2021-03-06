<?php

namespace kriss\modules\auth\tools;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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
            throw new ForbiddenHttpException(Yii::t('kriss', '没有访问权限:' . (is_array($name) ? implode('、', $name) : $name)));
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

    /**
     * 检查路由
     * @param $action
     * @return bool
     */
    public static function checkRoute($action)
    {
        $normalizeRoute = RouteHelper::normalizeRoute($action);
        if (is_array($action)) {
            $normalizeRoute = str_replace(ltrim(Url::base(), '/'), '', $normalizeRoute);
            $normalizeRoute = ltrim($normalizeRoute, '/');
        }
        return static::has($normalizeRoute);
    }

    /**
     * 过滤菜单
     * @param array $items item 中的 url 必须为完整路由（即不省略默认action），否则将匹配失败
     * @return array
     */
    public static function filterMenusRecursive($items)
    {
        $result = [];
        foreach ($items as $i => $item) {
            $url = ArrayHelper::getValue($item, 'url', '#');
            $allow = is_array($url) ? AuthValidate::checkRoute($url) : true;
            if (isset($item['items']) && is_array($item['items'])) {
                $subItems = static::filterMenusRecursive($item['items']);
                if (count($subItems)) {
                    $allow = true;
                }
                $item['items'] = $subItems;
            }
            if ($allow && !($url == '#' && empty($item['items']))) {
                $result[$i] = $item;
            }
        }
        return $result;
    }
}
