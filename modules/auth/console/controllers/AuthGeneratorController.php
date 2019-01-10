<?php

namespace kriss\modules\auth\console\controllers;

use kriss\modules\auth\components\CodeFile;
use Yii;
use yii\base\Exception;
use yii\console\Controller;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class AuthGeneratorController extends Controller
{
    const VERSION_1 = 1;
    const VERSION_2 = 2;

    public $genClass = 'common\models\base\Auth';
    public $baseClass = '\kriss\modules\auth\models\Auth';
    public $generateFile = '@common/models/base/Auth.php';
    public $templateFile = '@kriss/auth/console/template/template.php';
    /**
     * v1 参考 config-example_v1.php
     * v2 版本的，可以由 RouteScanController.php 生成
     * @var string
     */
    public $configFile = '@kriss/auth/console/template/config-example.php';
    public $canPermissionPermission = true;
    public $permissionId = 10000;
    public $roleId = 11000;
    public $startId = 12000;
    /**
     * @var int
     */
    public $version = self::VERSION_2;

    public function actionIndex()
    {
        $config = require Yii::getAlias($this->configFile);

        $this->logger($config);

        $data = $this->buildForCodeFile($config);

        $this->logger($data);

        $constArr = [];
        $msgArr = [];
        $initDataArr = [];
        foreach ($data as $module) {
            $key = $this->generateConstKey($module['key']);
            $value = $module['key'];
            $constArr[] = "const {$key} = '{$value}';";
            $msgArr[] = "static::{$key} => '{$module['name']}',";
            $initDataItem = ['id' => $module['id'], 'name' => "static::{$key}", 'children' => []];
            foreach ($module['items'] as $item) {
                $key = $this->generateConstKey($item['key']);
                $value = $item['key'];
                $constArr[] = "const {$key} = '{$value}';";
                $msgArr[] = "static::{$key} => '{$item['name']}',";
                $initDataItem['children'][] = ['id' => $item['id'], 'name' => "static::{$key}"];
            }
            $constArr[] = '';
            $msgArr[] = '';
            $initDataArr[] = $initDataItem;
        }

        array_pop($constArr);
        $this->logger($constArr);
        array_pop($msgArr);
        $this->logger($msgArr);
        $this->logger($initDataArr);

        $fileName = Yii::getAlias($this->generateFile);
        $file = new CodeFile(Yii::getAlias($this->generateFile), $this->renderFile(Yii::getAlias($this->templateFile), [
            'constArr' => $constArr,
            'msgArr' => $msgArr,
            'initDataArr' => $initDataArr,
            'generator' => $this,
        ]));
        $result = $file->save();
        if ($result === true) {
            return $this->stdout('Generate success. File path: ' . $fileName);
        }
        return $this->stderr($result);
    }

    protected function buildForCodeFile($config)
    {
        $data = [];
        if ($this->version == static::VERSION_2) {
            $id = $this->startId;
            $count = 1;
            foreach ($config as $prefix => $prefixData) {
                $newModule = [
                    'id' => $id,
                    'key' => $prefix,
                    'name' => $prefixData['name'],
                    'items' => [],
                ];
                foreach ($prefixData['items'] as $action => $actionData) {
                    if ($actionData['is_delete']) {
                        continue;
                    }
                    $newModule['items'][] = [
                        'id' => $id + $count,
                        'key' => "{$prefix}/{$action}",
                        'name' => $this->generateItemName($actionData['name'], $prefixData['name'], isset($item['nameFill']) ? $actionData['nameFill'] : ''),
                    ];
                    $count++;
                }
                if ($count > 1) {
                    $data[] = $newModule;
                } else {
                    continue;
                }

                $id += 1000;
                $count = 1;
            }
        } elseif ($this->version == static::VERSION_1) {
            foreach ($config as $id => $module) {
                $newModule = [
                    'id' => $id,
                    'key' => $module['key'],
                    'name' => $module['name'],
                    'items' => [],
                ];
                foreach ($module['items'] as $count => $item) {
                    $newModule['items'][] = [
                        'id' => $id . ($count + 1),
                        'key' => $module['key'] . ucfirst($item['key']),
                        'name' => $this->generateItemName($item['name'], $module['name'], isset($item['nameFill']) ? $item['nameFill'] : 'append'),
                    ];
                }
                $data[] = $newModule;
            }
        }

        return $data;
    }

    /**
     * 生成子权限的名字
     * @param $name
     * @param $moduleName
     * @param $nameFill
     * @return string
     */
    protected function generateItemName($name, $moduleName, $nameFill)
    {
        if ($nameFill == 'append') {
            $name = $name . $moduleName;
        } elseif ($nameFill == 'prepend') {
            $name = $moduleName . $name;
        }
        return $name;
    }

    protected function generateConstKey($key)
    {
        if ($this->version == static::VERSION_2) {
            return strtoupper(str_replace(['-', '/'], ['_', '__'], $key));
        } elseif ($this->version == static::VERSION_1) {
            return strtoupper(Inflector::camel2id($key, '_'));
        }
        throw new Exception('未知的版本');
    }

    /**
     * 获取 class 的名字
     * @param $class
     * @return string
     */
    public function getClassName($class)
    {
        return StringHelper::basename($class);
    }

    /**
     * 获取 class 的 namespace
     * @param $class
     * @return string
     */
    public function getClassNamespace($class)
    {
        $name = $this->getClassName($class);
        return ltrim(substr($class, 0, -(strlen($name) + 1)), '\\');
    }

    /**
     * @param $msg
     */
    protected function logger($msg)
    {
        Yii::debug(is_array($msg) ? json_encode($msg) : $msg);
    }
}
