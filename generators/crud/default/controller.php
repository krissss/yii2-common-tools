<?php
/** @var $this yii\web\View */
/** @var $generator \kriss\generators\crud\Generator */

$useClasses = $generator->getControllerUseClasses();
echo "<?php\n";
?>

namespace <?= $generator->getClassNamespace($generator->getControllerClass()) ?>;

<?php foreach ($useClasses as $useClass): ?>
use <?=$useClass?>;
<?php endforeach; ?>

class <?= $generator->getClassName($generator->getControllerClass()) ?> extends <?= $generator->getClassName($generator->controllerBaseClass) . "\n" ?>
{
    public function actions()
    {
        $actions = parent::actions();

        // 列表
        $actions['index'] = [
            'class' => IndexAction::class,
<?php if ($generator->searchAttributes): ?>
            'searchModel' => <?= $generator->getClassName($generator->getSearchClass()) ?>::class
<?php else: ?>
            'dataProvider' => [
                'query' => <?= $generator->modelName ?>::find(),
                'sort' => [
                    'defaultOrder' => [
<?=in_array('created_at', $generator->getColumnNames()) ? "                    'created_at' => SORT_DESC,\n" : ''?>
<?=in_array('id', $generator->getColumnNames()) ? "                    'id' => SORT_DESC,\n" : ''?>
                    ]
                ],
            ]
<?php endif; ?>
        ];
<?php if ($generator->hasCreate): ?>
        // 新增
        $actions['create'] = [
            'class' => CreateAction::class,
            'modelClass' => <?= $generator->modelName ?>::class,
            'isAjax' => <?= $generator->useAjax ? 'true' : 'false' ?>,
            'view' => '<?= $generator->getCreateUpdateViewName() ?>',
        ];
<?php endif; ?>
<?php if ($generator->hasUpdate): ?>
        // 修改
        $actions['update'] = [
            'class' => UpdateAction::class,
            'modelClass' => <?= $generator->modelName ?>::class,
            'isAjax' => <?= $generator->useAjax ? 'true' : 'false' ?>,
            'view' => '<?= $generator->getCreateUpdateViewName() ?>',
        ];
<?php endif; ?>
<?php if ($generator->hasView): ?>
        // 详情
        $actions['view'] = [
            'class' => ViewAction::class,
            'modelClass' => <?= $generator->modelName ?>::class,
            'isAjax' => <?= $generator->useAjax ? 'true' : 'false' ?>,
            'view' => '<?= $generator->getViewViewName() ?>',
        ];
<?php endif; ?>
<?php if ($generator->hasDelete): ?>
        // 删除
        $actions['delete'] = [
            'class' => DeleteAction::class,
            'modelClass' => <?= $generator->modelName ?>::class,
        ];
<?php endif; ?>

        return $actions;
    }
}
