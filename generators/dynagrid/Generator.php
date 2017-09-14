<?php

namespace kriss\generators\dynagrid;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class Generator extends \yii\gii\Generator
{
    public $controllerClass = 'backend\controllers\XXXController';

    public $controllerBaseClass = 'backend\components\{Auth|Base}WebController';

    public $activeDataProviderClass = 'common\components\ActiveDataProvider';

    public $searchModelClass = 'backend\models\XXXSearch';

    public $modelClass = 'common\models\User';

    public $searchAttributes = 'id,created_at';

    public $actionIndex = 'index';

    public $title = 'xxx';

    public $dataColumns = 'id,name';

    public $actionColumns = 'update:更新,view:详情';

    public function getName()
    {
        return 'Dynagrid Generator';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [[
                'controllerClass', 'controllerBaseClass', 'activeDataProviderClass', 'searchModelClass', 'modelClass',
                'searchAttributes', 'actionIndex', 'title', 'dataColumns', 'actionColumns'
            ], 'safe']
        ]);
    }

    public function hints()
    {
        return array_merge(parent::hints(), [
            'controllerClass' => '控制器类, (e.g. <code>backend\controllers\ArticleController</code>)',
            'controllerBaseClass' => '控制器继承类, (e.g. <code>backend\controller\AuthWebController</code>)',
            'activeDataProviderClass' => '数据提供器类, (e.g. <code>common\components\ActiveDataProvider</code>)',
            'searchModelClass' => '查询模型类,可以为空, (e.g. <code>backend\models\ArticleSearch</code>)',
            'modelClass' => '查询模型继承类,必须是 yii\db\ActiveRecord 子类,searchModelClass 为空时无用, (e.g. <code>common\models\Article</code>)',
            'searchAttributes' => '查询的字段, (e.g. <code>id,created_at,title</code>)',
            'actionIndex' => '列表页 action 的id, (e.g. <code>index</code>)',
            'title' => '列表页面的标题, (e.g. <code>文章管理</code>)',
            'dataColumns' => '列表页面的 DataColumn 属性字段, (e.g. <code>id,name</code>)',
            'actionColumns' => '列表页面的 ActionColumn 属性字段,可以为空, (e.g. <code>update:更新,view:详情</code>)',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['controllerBaseClass', 'activeDataProviderClass', 'actionIndex']);
    }

    public function generate()
    {
        $files = [];

        $files[] = new CodeFile(
            $this->getClassFile($this->controllerClass),
            $this->render('controller.php')
        );

        if (!empty($this->searchModelClass)) {
            $files[] = new CodeFile(
                $this->getClassFile($this->searchModelClass),
                $this->render('search.php')
            );
            $files[] = new CodeFile(
                $this->getViewFile('_search'),
                $this->render('views/_search.php')
            );
        }

        $files[] = new CodeFile(
            $this->getViewFile($this->actionIndex),
            $this->render('views/index.php', ['action' => $this->actionIndex])
        );

        return $files;
    }

    /**
     * 控制器use的类
     * @return array
     */
    public function getControllerUseClasses()
    {
        if($this->searchModelClass){
            $useClasses = [
                'Yii',
                $this->controllerBaseClass,
                $this->searchModelClass,
            ];
        }else{
            $useClasses = [
                $this->controllerBaseClass,
                $this->activeDataProviderClass,
                $this->modelClass,
            ];
        }
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
            $this->modelClass,
            $this->activeDataProviderClass
        ];
        natcasesort($useClasses);
        return array_values($useClasses);
    }

    /**
     * index 页面的 data columns
     * @return array
     */
    public function getDataColumns()
    {
        return explode(',', $this->dataColumns);
    }

    /**
     * index 页面的 action columns
     * @return array
     */
    public function getActionColumns()
    {
        $array = [];
        $temp1 = explode(',', $this->actionColumns);
        foreach ($temp1 as $item) {
            $temp2 = explode(':', $item);
            $array[$temp2[0]] = Inflector::camel2id($temp2[1]);
        }
        return $array;
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
     * 获取表的字段
     * @return array
     * @throws Exception
     */
    public function getColumnNames()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema()->getColumnNames();
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
        $name = StringHelper::basename($this->controllerClass);
        return Inflector::camel2id(substr($name, 0, strlen($name) - 10));
    }

    /**
     * 获取 视图 文件
     * @param $action
     * @return bool|string
     */
    public function getViewFile($action)
    {
        return Yii::getAlias('@app/views/' . $this->getControllerID() . "/$action.php");
    }

    /**
     * 获取 表 的结构
     * @return bool|\yii\db\TableSchema
     */
    public function getTableSchema()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema();
        } else {
            return false;
        }
    }
}