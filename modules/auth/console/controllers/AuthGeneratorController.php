<?php

namespace kriss\modules\auth\console\controllers;

use kriss\modules\auth\components\CodeFile;
use Yii;
use yii\console\Controller;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class AuthGeneratorController extends Controller
{
    public $genClass = 'common\models\base\Auth';
    public $baseClass = '\kriss\modules\auth\models\Auth';
    public $generateFile = '@common/models/base/Auth.php';
    public $templateFile = '@kriss/auth/console/template/template.php';
    public $configFile = '@kriss/auth/console/template/config-example.php';
    public $canPermissionPermission = true;
    public $permissionId = 10;
    public $roleId = 20;

    public function actionIndex()
    {
        $config = require Yii::getAlias($this->configFile);

        $this->logger($config);

        $data = [];
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

        $this->logger($data);

        $constArr = [];
        $msgArr = [];
        $initDataArr = [];
        foreach ($data as $module) {
            $key = strtoupper(Inflector::camel2id($module['key'], '_'));
            $value = $module['key'];
            $constArr[] = "const {$key} = '{$value}';";
            $msgArr[] = "static::{$key} => '{$module['name']}',";
            $initDataItem = ['id' => $module['id'], 'name' => "static::{$key}", 'children' => []];
            foreach ($module['items'] as $item) {
                $key = strtoupper(Inflector::camel2id($item['key'], '_'));
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
        Yii::trace(is_array($msg) ? json_encode($msg) : $msg);
    }
}
