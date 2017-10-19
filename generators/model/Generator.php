<?php

namespace kriss\generators\model;

use Yii;
use yii\gii\CodeFile;

class Generator extends \yii\gii\generators\model\Generator
{
    public $generateDao = true;
    public $daoNs = 'common\models\dao';
    public $skipTables = 'migration';

    public $ns = 'common\models';
    public $baseClass = 'common\models\base\ActiveRecord';
    public $generateLabelsFromComments = true;
    public $queryNs = 'common\models\query';

    public function init()
    {
        Yii::$app->setAliases([
            '@gii-model' => '@vendor/yiisoft/yii2-gii/generators/model',
            '@gii-model-view' => '@vendor/yiisoft/yii2-gii/generators/model/default'
        ]);
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Kriss Model Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates an ActiveRecord class for the specified database table.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['generateDao', 'boolean'],
            ['daoNs', 'validateNamespace'],
            ['skipTables', 'string']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'generateDao' => 'Generate Dao',
            'daoNs' => 'Dao Namespace',
            'skipTables' => 'Skip Table'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'generateDao' => 'This indicates whether to generate ActiveQuery for the ActiveRecord class.',
            'daoNs' => 'This is the namespace of the ActiveQuery class to be generated, e.g., <code>app\models</code>',
            'skipTables' => 'which table or tables that skip to render, split by <code>,</code>, e.g., <code>migration</code>',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['generateDao', 'daoNs', 'skipTables']);
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        if (!$this->generateDao) {
            return parent::generate();
        }

        $files = [];
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName) {
            if (in_array($tableName, $this->getSkipTableNames())) {
                continue;
            }
            // dao :
            $modelClassName = $this->generateClassName($tableName);
            $daoClassName = $this->generateDaoClassName($modelClassName);
            $queryClassName = ($this->generateQuery) ? $this->generateQueryClassName($modelClassName) : false;
            $tableSchema = $db->getTableSchema($tableName);
            $params = [
                'tableName' => $tableName,
                'className' => $daoClassName,
                'queryClassName' => $queryClassName,
                'tableSchema' => $tableSchema,
                'labels' => $this->generateLabels($tableSchema),
                'rules' => $this->generateRules($tableSchema),
                'relations' => isset($relations[$tableName]) ? $relations[$tableName] : [],
            ];
            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->daoNs)) . '/' . $daoClassName . '.php',
                $this->render('dao.php', $params)
            );

            // model :
            $modelClassName = $this->generateClassName($tableName);
            $params = [
                'className' => $modelClassName,
                'daoClassName' => $daoClassName,
                'tableSchema' => $tableSchema,
                'relations' => isset($relations[$tableName]) ? $relations[$tableName] : [],
            ];
            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $modelClassName . '.php',
                $this->render('model.php', $params)
            );

            // query :
            if ($queryClassName) {
                $params['className'] = $queryClassName;
                $params['modelClassName'] = $modelClassName;
                $files[] = new CodeFile(
                    Yii::getAlias('@' . str_replace('\\', '/', $this->queryNs)) . '/' . $queryClassName . '.php',
                    $this->render('query.php', $params)
                );
            }
        }

        return $files;
    }

    /**
     * @param string $modelClassName
     * @return string
     */
    protected function generateDaoClassName($modelClassName)
    {
        $queryClassName = $this->queryClass;
        if (empty($queryClassName) || strpos($this->tableName, '*') !== false) {
            $queryClassName = $modelClassName . 'Dao';
        }
        return $queryClassName;
    }

    /**
     * @return array
     */
    protected function getSkipTableNames()
    {
        return explode(',', $this->skipTables);
    }
}
