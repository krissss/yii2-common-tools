<?php

namespace kriss\modules\auth\console\controllers;

use kriss\modules\auth\tools\RouteHelper;
use Yii;
use yii\base\NotSupportedException;
use yii\console\controllers\HelpController;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;

/**
 * 该类必须得放在对应的模块下访问才能生成文件
 * 比如要生成 backend 目录下的权限配置的，就得放到 backend 的 controllerMap 中
 */
class RouteScanController extends HelpController
{
    public $skipRoutes = [
        '*/previous-redirect',
        'gii/*',
        'debug/*',
        'dynagrid/*',
        'gridview/*',
        'datecontrol/*',
        'log-reader/*',
        'auth/*',
        'site/*',
    ];

    public $nameMap = [
        'admin' => '管理员管理',
        'create' => '新增',
        'update' => '修改',
        'delete' => '删除',
        'view' => '查看',
        'index' => '列表',
    ];

    public $saveFile = '@common/models/base/auth-config.php';

    public function actionIndex($command = null)
    {
        throw new NotSupportedException();
    }

    public function actionListActionOptions($action)
    {
        throw new NotSupportedException();
    }

    public function actionList()
    {
        return $this->getPrefixAndActions();
    }

    public function actionGenerateFile()
    {
        $config = $this->getPrefixAndActions();
        $result = [];
        foreach ($config as $prefix => $actions) {
            $result[$prefix] = [
                'name' => $this->guessName($prefix),
                'items' => [],
            ];
            foreach ($actions as $action) {
                $result[$prefix]['items'][$action] = [
                    'name' => $this->guessName($action),
                    'is_delete' => false,
                ];
            }
        }
        $this->saveConfigToFile($result);
    }

    protected function saveConfigToFile($config)
    {
        $saveFile = Yii::getAlias($this->saveFile);
        if (file_exists($saveFile)) {
            $oldConfig = require_once $saveFile;
            foreach ($oldConfig as $prefix => &$prefixData) {
                if (isset($config[$prefix]) && $prefixData['name']) {
                    // 保留旧数据的 name 字段
                    $config[$prefix]['name'] = $prefixData['name'];
                }
                foreach ($prefixData['items'] as $action => &$actionData) {
                    if (isset($config[$prefix], $config[$prefix]['items'][$action]) && $actionData['name']) {
                        // 保留旧数据的 name 字段
                        $config[$prefix]['items'][$action]['name'] = $actionData['name'];
                    }
                    $actionData['is_delete'] = true;
                }
            }
            $config = ArrayHelper::merge($oldConfig, $config);
        }
        $contentStr = VarDumper::export($config);
        $content = <<<PHP
<?php

return {$contentStr};
PHP;
        file_put_contents($saveFile, $content);
    }

    protected function getPrefixAndActions()
    {
        $routeHelper = RouteHelper::create($this->skipRoutes);
        $config = [];
        foreach ($this->getCommands() as $command) {
            $result = Yii::$app->createController($command);
            if ($result === false || !($result[0] instanceof Controller)) {
                continue;
            }
            /** @var $controller Controller|\yii\console\Controller */
            list($controller) = $result;
            $actions = $this->getActions($controller);
            if (!empty($actions)) {
                $prefix = $controller->getUniqueId();
                if ($routeHelper->isMatchPrefix($prefix)) {
                    continue;
                }
                if (!isset($config[$prefix])) {
                    $config[$prefix] = [];
                }
                foreach ($actions as $action) {
                    if ($routeHelper->isMatchAction($prefix, $action)) {
                        continue;
                    }
                    $config[$prefix][] = $action;
                }
            }
        }
        return $config;
    }

    protected function guessName($name)
    {
        return isset($this->nameMap[$name]) ? $this->nameMap[$name] : $name;
    }

    /**
     * @inheritdoc
     */
    protected function validateControllerClass($controllerClass)
    {
        if (class_exists($controllerClass)) {
            $class = new \ReflectionClass($controllerClass);
            return !$class->isAbstract() && $class->isSubclassOf('yii\web\Controller');
        }

        return false;
    }
}
