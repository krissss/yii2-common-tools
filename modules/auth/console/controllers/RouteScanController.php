<?php

namespace kriss\modules\auth\console\controllers;

use kriss\modules\auth\tools\RouteHelper;
use Yii;
use yii\base\NotSupportedException;
use yii\console\controllers\HelpController;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;

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
        'route-scan/*',
    ];

    /**
     * @see guessName()
     * @var array
     */
    public $nameMap = [
        'admin' => '管理员管理',
        'create' => '新增',
        'update' => '修改',
        'delete' => '删除',
        'view' => '详情',
        'detail' => '详情',
        'index' => '列表',
    ];
    /**
     * nameMap 中无值时使用
     * #key# 代表使用原key
     * @see guessName()
     * @var string
     */
    public $nameMapLost = '#key#';

    /**
     * 无法通过 action 扫到的特殊路由
     * 例如 导出
     * 可以定义为模块下一个不存在的 action 名，例如：
     * [
     *   'shop-goods' => ['ext-export'],
     * ]
     * @var array
     */
    public $extraRoutes = [];

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
        Yii::$app->response->format = Response::FORMAT_JSON;
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
            if (isset($this->extraRoutes[$prefix])) {
                foreach ($this->extraRoutes[$prefix] as $action) {
                    if (!isset($result[$prefix]['items'][$action])) {
                        $result[$prefix]['items'][$action] = [
                            'name' => $this->guessName($action),
                            'is_delete' => false,
                        ];
                    }
                }
            }
        }
        $this->saveConfigToFile($result);

        return 'ok';
    }

    protected function saveConfigToFile($config)
    {
        $saveFile = Yii::getAlias($this->saveFile);
        if (file_exists($saveFile)) {
            $oldConfig = require_once $saveFile;
            foreach ($oldConfig as $prefix => &$prefixData) {
                if (isset($config[$prefix]) && $prefixData['name'] !== $this->guessName($prefix)) {
                    // 保留旧数据的 name 字段
                    $config[$prefix]['name'] = $prefixData['name'];
                }
                foreach ($prefixData['items'] as $action => &$actionData) {
                    if (isset($config[$prefix], $config[$prefix]['items'][$action]) && $actionData['name'] !== $this->guessName($action)) {
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
            $command = str_replace('\\', '/', $command);
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
        if (isset($this->nameMap[$name])) {
            return $this->nameMap[$name];
        }
        if ($this->nameMapLost == '#key#') {
            return $name;
        }
        return $this->nameMapLost;
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

    /**
     * @inheritdoc
     */
    public function getCommands()
    {
        $commands = $this->getModuleCommands(Yii::$app);
        sort($commands);
        return array_filter(array_unique($commands), function ($command) {
            $result = Yii::$app->createController($command);
            if ($result === false || !$result[0] instanceof Controller) {
                return false;
            }
            list($controller, $actionID) = $result;
            $actions = $this->getActions($controller);
            return $actions !== [];
        });
    }
}
