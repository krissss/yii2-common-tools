<?php
/**
 * @var $this yii\web\View
 * @var $model \kriss\modules\auth\models\AuthRole
 * @var $operations array
 */

use kriss\modules\auth\models\AuthOperation;
use yii\helpers\Html;

$this->title = Yii::t('kriss', '角色详情');
$this->params['breadcrumbs'] = [
    Yii::t('kriss', '权限管理'),
    [
        'label' => Yii::t('kriss', '角色管理'),
        'url' => ['index'],
    ],
    $this->title,
];

$operationListArr = explode(';', $model->operation_list);
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title ?></h3>
    </div>
    <div class="box-body">
        <table class="table table-striped table-bordered">
            <tbody>
            <tr>
                <th><?= Yii::t('kriss', '模块') ?></th>
                <th><?= Yii::t('kriss', '权限') ?></th>
            </tr>
            <?php foreach ($operations as $operation) : ?>
                <?php
                /** @var AuthOperation $module */
                $module = $operation['model'];
                $moduleSelected = in_array($module->name, $operationListArr) ? [$module->name] : [];
                /** @var AuthOperation[] $items */
                $items = $operation['items'];
                $subItems = [];
                $subItemSelected = [];
                foreach ($items as $item) {
                    $subItems[$item->name] = $item->getViewName();
                    if (in_array($item->name, $operationListArr)) {
                        $subItemSelected[] = $item->name;
                    }
                }
                ?>
                <tr>
                    <td width="150px">
                        <?= Html::checkboxList('_operations', $moduleSelected, [
                            $module->name => $module->getViewName()
                        ], ['itemOptions' => ['disabled' => true]]) ?>
                    </td>
                    <td><?= Html::checkboxList('_operations', $subItemSelected, $subItems, ['itemOptions' => ['disabled' => true]]) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
