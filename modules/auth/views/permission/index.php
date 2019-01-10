<?php
/**
 * @var $operations array
 */

$this->title = Yii::t('kriss', '权限');
$this->params['breadcrumbs'] = [
    Yii::t('kriss', '权限管理'),
    $this->title,
];

use kriss\modules\auth\models\AuthOperation;
use yii\helpers\Html; ?>
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
                /** @var AuthOperation[] $items */
                $items = $operation['items'];
                $subItems = [];
                foreach ($items as $item) {
                    $subItems[$item->name] = $item->getViewName();
                }
                ?>
                <tr>
                    <td width="150px">
                        <?= Html::checkboxList('_operations', null, [
                            $module->name => $module->getViewName()
                        ], ['itemOptions' => ['disabled' => true]]) ?>
                    </td>
                    <td><?= Html::checkboxList('_operations', null, $subItems, ['itemOptions' => ['disabled' => true]]) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
