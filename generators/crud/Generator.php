<?php

namespace kriss\generators\crud;

use kriss\actions\web\crud\CreateAction;
use kriss\actions\web\crud\DeleteAction;
use kriss\actions\web\crud\IndexAction;
use kriss\actions\web\crud\UpdateAction;
use kriss\actions\web\crud\ViewAction;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class Generator extends \yii\gii\Generator
{
    public $modelName = '';

    public $modelLabel = '';

    public $modelPath = '@common/models';

    public $controllerPath = '@backend/controllers';

    public $viewPath = '@app/views';

    public $searchModelPath = '@backend/models';

    public $controllerBaseClass = 'backend\components\AuthWebController';

    public $searchAttributes = '';

    public $hasCreate = true;
    public $hasUpdate = true;
    public $hasView = true;
    public $hasDelete = true;

    public $useAjax = true;

    public $hasCheckboxColumn = false;

    public function getName()
    {
        return 'kriss Crud Generator';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['modelName', 'modelLabel'], 'required'],
            [[
                'modelName', 'modelLabel', 'modelPath', 'controllerPath', 'viewPath', 'searchModelPath',
                'controllerBaseClass', 'searchAttributes',
            ], 'safe'],
            [['hasCreate', 'hasUpdate', 'hasView', 'hasDelete', 'useAjax', 'hasCheckboxColumn'], 'boolean']
        ]);
    }

    public function hints()
    {
        return array_merge(parent::hints(), [
            'modelName' => '模型类名, (e.g. <code>Article</code>)',
            'modelLabel' => '模型名称, (e.g. <code>文章</code>)',
            'modelPath' => '模型文件路劲, (e.g. <code>@common/models</code>)',
            'controllerPath' => '控制器文件路劲, (e.g. <code>@backend/controllers</code>)',
            'viewPath' => '视图文件路劲, (e.g. <code>@app/views</code>)',
            'searchModelPath' => '查询模型文件路劲, (e.g. <code>@backend/models</code>)',
            'controllerBaseClass' => '基础控制器类, (e.g. <code>yii\web\Controller</code>)',
            'searchAttributes' => '查询的字段，若该字段为空，则不使用查询模型, (e.g. <code>id,name</code>)',
            'hasCreate' => '是否有新增',
            'hasUpdate' => '是否有修改',
            'hasView' => '是否有详情',
            'hasDelete' => '是否有删除',
            'useAjax' => '是否使用 ajax 模式',
            'hasCheckboxColumn' => '是否有复选框',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), [
            'modelPath',
            'controllerPath',
            'viewPath',
            'searchModelPath',
            'controllerBaseClass',
        ]);
    }

    public function generate()
    {
        $files = [];

        $files[] = new CodeFile(
            $this->getClassFile($this->getControllerClass()),
            $this->render('controller.php')
        );

        $files[] = new CodeFile(
            $this->getViewFile('index'),
            $this->render('views/index.php')
        );

        if (!empty($this->searchAttributes)) {
            $files[] = new CodeFile(
                $this->getClassFile($this->getSearchClass()),
                $this->render('search.php')
            );
            $files[] = new CodeFile(
                $this->getViewFile('_search'),
                $this->render('views/_search.php')
            );
        }

        if ($this->hasCreate || $this->hasUpdate) {
            $viewName = $this->getCreateUpdateViewName();
            $files[] = new CodeFile(
                $this->getViewFile($viewName),
                $this->render('views/' . $viewName . '.php')
            );
        }

        if ($this->hasView) {
            $viewName = $this->getViewViewName();
            $files[] = new CodeFile(
                $this->getViewFile($viewName),
                $this->render('views/' . $viewName . '.php')
            );
        }

        return $files;
    }

    /**
     * @return string
     */
    public function getCreateUpdateViewName()
    {
        return $this->useAjax ? '_create_update' : 'create_update';
    }

    /**
     * @return string
     */
    public function getViewViewName()
    {
        return $this->useAjax ? '_view' : 'view';
    }

    /**
     * @var string
     */
    private $_modelClass;

    /**
     * @return string
     */
    public function getModelClass()
    {
        if ($this->_modelClass) {
            return $this->_modelClass;
        }
        $this->_modelClass = $this->getClassFromPathAndName($this->modelPath, $this->modelName);
        return $this->_modelClass;
    }

    /**
     * @var string
     */
    private $_controllerClass;

    /**
     * @return string
     */
    public function getControllerClass()
    {
        if ($this->_controllerClass) {
            return $this->_controllerClass;
        }
        $this->_controllerClass = $this->getClassFromPathAndName($this->controllerPath, $this->modelName . 'Controller');
        return $this->_controllerClass;
    }

    /**
     * @var string
     */
    private $_searchClass;

    /**
     * @return string
     */
    public function getSearchClass()
    {
        if ($this->_searchClass) {
            return $this->_searchClass;
        }
        $this->_searchClass = $this->getClassFromPathAndName($this->searchModelPath, $this->modelName . 'Search');
        return $this->_searchClass;
    }

    /**
     * 从路径和名称获取class
     * @param $path
     * @param $name
     * @return mixed
     */
    protected function getClassFromPathAndName($path, $name)
    {
        if (substr($path, 0, 1) == '@') {
            $path = substr($path, 1, strlen($path));
        }
        $class = rtrim($path, '/') . '/' . $name;
        return str_replace('/', '\\', $class);
    }

    /**
     * 控制器use的类
     * @return array
     */
    public function getControllerUseClasses()
    {
        $useClasses = [
            $this->controllerBaseClass,
            IndexAction::class,
        ];
        if ($this->searchAttributes) {
            $useClasses[] = $this->getSearchClass();
        }
        if ($this->hasCreate) {
            $useClasses[] = CreateAction::class;
            $useClasses[] = $this->getModelClass();
        }
        if ($this->hasUpdate) {
            $useClasses[] = UpdateAction::class;
            $useClasses[] = $this->getModelClass();
        }
        if ($this->hasView) {
            $useClasses[] = ViewAction::class;
            $useClasses[] = $this->getModelClass();
        }
        if ($this->hasDelete) {
            $useClasses[] = DeleteAction::class;
            $useClasses[] = $this->getModelClass();
        }

        $useClasses = array_unique($useClasses);
        natcasesort($useClasses);

        return array_values($useClasses);
    }

    /**
     * 查询类use的类
     * @return array
     */
    public function getSearchModelUseClasses()
    {
        $useClasses = [
            $this->getModelClass(),
            ActiveDataProvider::class,
        ];
        natcasesort($useClasses);
        return array_values($useClasses);
    }

    /**
     * 生成查询 model 的 rules
     * @return array
     */
    public function generateSearchRules()
    {
        $searchAttributes = $this->getSearchAttributes();
        $table = $this->getTableSchema();
        $types = [];
        foreach ($table->columns as $column) {
            if (!in_array($column->name, $searchAttributes)) {
                continue;
            }
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $column->name;
                    break;
                case Schema::TYPE_STRING:
                    $types['string'][] = $column->name;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                default:
                    $types['safe'][] = $column->name;
                    break;
            }
        }

        $rules = [];
        foreach ($types as $type => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }

        return $rules;
    }

    /**
     * 生成查询的 query
     * @return array
     */
    public function generateSearchConditions()
    {
        $searchAttributes = $this->getSearchAttributes();
        $table = $this->getTableSchema();
        $likeConditions = [];
        $hashConditions = [];
        foreach ($table->columns as $column) {
            if (!in_array($column->name, $searchAttributes)) {
                continue;
            }
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_BOOLEAN:
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                    $hashConditions[] = "'{$column->name}' => \$this->{$column->name},";
                    break;
                default:
                    $likeConditions[] = "->andFilterWhere(['like', '{$column->name}', \$this->{$column->name}])";
                    break;
            }
        }

        $conditions = [];
        if (!empty($hashConditions)) {
            $conditions[] = "\$query->andFilterWhere([\n"
                . str_repeat(' ', 12) . implode("\n" . str_repeat(' ', 12), $hashConditions)
                . "\n" . str_repeat(' ', 8) . "]);\n";
        }
        if (!empty($likeConditions)) {
            $conditions[] = "\$query" . implode("\n" . str_repeat(' ', 12), $likeConditions) . ";\n";
        }

        return $conditions;
    }

    /**
     * 获取查询的字段
     * @return array
     */
    public function getSearchAttributes()
    {
        $modelAttributes = $this->getColumnNames();
        $searchAttributes = explode(',', $this->searchAttributes);
        return array_intersect($searchAttributes, $modelAttributes);
    }

    /**
     * @var array
     */
    private $_columnNames;

    /**
     * 获取表的字段
     * @return array
     * @throws Exception
     */
    public function getColumnNames()
    {
        if ($this->_columnNames) {
            return $this->_columnNames;
        }
        /* @var $class ActiveRecord */
        $class = $this->getModelClass();
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            $this->_columnNames = $class::getTableSchema()->getColumnNames();
            return $this->_columnNames;
        } else {
            throw new Exception('必须是 yii\db\ActiveRecord 的子类');
        }
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
     * 获取 class 文件
     * @param $class
     * @return string
     */
    public function getClassFile($class)
    {
        return Yii::getAlias('@' . str_replace('\\', '/', $class)) . '.php';
    }

    /**
     * 获取控制器 id
     * @return string
     */
    public function getControllerID()
    {
        return Inflector::camel2id($this->modelName);
    }

    /**
     * 获取 视图 文件
     * @param $action
     * @return bool|string
     */
    public function getViewFile($action)
    {
        if (!$this->viewPath) {
            $viewPath = Yii::getAlias("@app/views");
        } else {
            $viewPath = Yii::getAlias(rtrim(str_replace('\\', '/', $this->viewPath), '/'));
        }

        return "{$viewPath}/{$this->getControllerID()}/{$action}.php";
    }

    /**
     * 获取 表 的结构
     * @return bool|\yii\db\TableSchema
     */
    public function getTableSchema()
    {
        /* @var $class ActiveRecord */
        $class = $this->getModelClass();
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema();
        } else {
            return false;
        }
    }
}
