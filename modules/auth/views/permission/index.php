<?php
/** @var $operations array */

$this->title = '权限';
$this->params['breadcrumbs'] = [
    '权限管理',
    $this->title,
];
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title ?></h3>
    </div>
    <div class="box-body">
        <table class="table table-striped table-bordered">
            <tbody>
            <tr>
                <th>模块</th>
                <th>权限</th>
            </tr>
            <?php foreach ($operations as $operation) : ?>
                <tr>
                    <td width="150px">
                        <?= Yii::t('app', $operation['name']) ?>
                    </td>
                    <td>
                        <?=implode(' | ',$operation['sub'])?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>