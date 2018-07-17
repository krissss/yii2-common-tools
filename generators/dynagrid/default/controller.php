<?php

use yii\helpers\Inflector;

/** @var $this yii\web\View */
/** @var $generator \kriss\generators\dynagrid\Generator */

$useClasses = $generator->getControllerUseClasses();
$operateActions = $generator->getActionColumns();
$toolbarActions = $generator->getToolbarActions();
echo "<?php\n";
?>

namespace <?= $generator->getClassNamespace($generator->controllerClass) ?>;

<?php foreach ($useClasses as $useClass): ?>
use <?=$useClass?>;
<?php endforeach; ?>

class <?= $generator->getClassName($generator->controllerClass) ?> extends <?= $generator->getClassName($generator->controllerBaseClass) . "\n" ?>
{
    // 列表
    public function action<?= Inflector::id2camel($generator->actionIndex) ?>()
    {
        $this->rememberUrl();

<?php if ($generator->searchModelClass): ?>
        $searchModel = new <?=$generator->getClassName($generator->searchModelClass)?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('<?= $generator->actionIndex ?>', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
<?php else: ?>
        $dataProvider = new <?=$generator->getClassName($generator->activeDataProviderClass)?>([
            'query' => <?= $generator->getClassName($generator->modelClass) ?>::find(),
            'sort' => [
                'defaultOrder' => [
<?=in_array('created_at', $generator->getColumnNames()) ? "                    'created_at' => SORT_DESC,\n" : ''?>
<?=in_array('id', $generator->getColumnNames()) ? "                    'id' => SORT_DESC,\n" : ''?>
                ]
            ]
        ]);
        return $this->render('<?= $generator->actionIndex ?>', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

<?php foreach ($toolbarActions as $action => $label): ?>
    // <?=$label . "\n"?>
    public function action<?= Inflector::id2camel($action) ?>()
    {
        // TODO
    }

<?php endforeach; ?>
<?php foreach ($operateActions as $action => $label): ?>
    // <?=$label . "\n"?>
    public function action<?= Inflector::id2camel($action) ?>($id)
    {
        // TODO
    }

<?php endforeach; ?>

    /**
     * @param $id
     * @return CompanyQuestion
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = <?= $generator->getClassName($generator->modelClass) ?>::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}
