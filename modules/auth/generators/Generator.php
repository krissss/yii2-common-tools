<?php

namespace kriss\modules\auth\generators;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\StringHelper;
use yii\helpers\Inflector;

class Generator extends \yii\gii\Generator
{
    public $authClass = 'common\models\base\Auth';
    public $moduleId;
    public $moduleKey;
    public $moduleName;
    public $childOperations = 'view=>查看,create=>新增,update=>修改,delete=>删除';
    public $useModulePrefix = true;
    public $baseClass = '\kriss\modules\auth\models\Auth';

    private $childOptionsArr = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['authClass', 'moduleId', 'moduleKey', 'moduleName', 'useModulePrefix', 'childOperations', 'baseClass'], 'required'],
            ['moduleId', 'number']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return [
            'authClass' => 'model class e.g. <code>common\models\base\Auth</code>',
            'moduleId' => 'number e.g. <code>10001</code>',
            'moduleKey' => 'parent key e.g. <code>superAdmin</code>',
            'moduleName' => 'parent label e.g. <code>超级管理员</code>',
            'childOperations' => 'child operations e.g. <code>view=>查看,create=>新增</code>',
            'useModulePrefix' => 'is child operations use parent prefix',
            'baseClass' => 'model class which is based e.g. <code>\kriss\modules\auth\models\Auth</code>',
        ];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return ['authClass', 'childOperations', 'baseClass'];
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'kriss auth generator';
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        return [
            new CodeFile($this->getAuthFile(), $this->render('Auth.php'))
        ];
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return [
            'Auth.php',
        ];
    }

    /**
     * @return string
     */
    public function getAuthFile()
    {
        return Yii::getAlias('@' . str_replace('\\', '/', $this->authClass)) . '.php';
    }

    /**
     * @return string
     */
    public function getAuthNamespace()
    {
        $name = StringHelper::basename($this->authClass);
        return ltrim(substr($this->authClass, 0, -(strlen($name) + 1)), '\\');
    }

    /**
     * @return array
     */
    public function getChildOperationKeys()
    {
        $operationArr = $this->getChildOperationArr();
        return array_keys($operationArr);
    }

    /**
     * @return array
     */
    public function getChildOperationNames()
    {
        $operationArr = $this->getChildOperationArr();
        return array_values($operationArr);
    }

    /**
     * @param $str
     * @return string
     */
    public function getConstName($str, $isChild = false)
    {
        $name = '';
        if ($isChild && $this->useModulePrefix) {
            $name .= strtoupper(Inflector::underscore(trim($this->moduleKey))) . '_';
        }
        return $name . strtoupper(Inflector::underscore(trim($str)));
    }

    /**
     * @param $str
     * @return string
     */
    public function getConstValue($str)
    {
        $value = '';
        if ($this->useModulePrefix) {
            $value .= Inflector::variablize(trim($this->moduleKey)) . '_';
        }
        return Inflector::variablize($value . trim($str));
    }

    /**
     * @return array
     */
    protected function getChildOperationArr()
    {
        if (count($this->childOptionsArr) > 0) {
            return $this->childOptionsArr;
        }
        $operationStrings = explode(',', trim($this->childOperations));
        foreach ($operationStrings as $operationString) {
            $arr = explode('=>', trim($operationString));
            $this->childOptionsArr[trim($arr[0])] = trim($arr[1]);
        }
        return $this->childOptionsArr;
    }
}