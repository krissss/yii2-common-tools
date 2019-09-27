<?php
/**
 * @var $this \yii\web\View
 * @var $name string
 * @var $message string
 * @var $logo string
 */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="login-box">
    <?php if ($logo): ?>
        <div class="login-logo">
            <a href="javascript:void(0);">
                <?= Html::img($logo, [
                    'alt' => Yii::$app->name,
                    'style' => 'max-width: 80%;'
                ]) ?>
            </a>
        </div>
    <?php endif; ?>
    <div class="login-box-body">
        <h3><?= $name ?></h3>
        <p><?= $message ?></p>
    </div>
</div>
