<?php
/* @var $this yii\web\View */
/* @var $generator \kriss\modules\auth\generators\Generator */

use yii\helpers\StringHelper;

echo "<?php\n";
?>

namespace <?= $generator->getAuthNamespace() ?>;

class <?= StringHelper::basename($generator->authClass) ?> extends <?= '\\' . trim($generator->baseClass, '\\') . "\n" ?>
{
    const <?= $generator->getConstName($generator->moduleKey) ?> = '<?= $generator->moduleKey ?>';
<?php foreach ($generator->getChildOperationKeys() as $operationKey): ?>
    const <?=$generator->getConstName($operationKey, true)?> = '<?=$generator->getConstValue($operationKey)?>';
<?php endforeach;?>

    public static function getMessageData()
    {
        $old = parent::getMessageData();

        $new = [
            self::<?= $generator->getConstName($generator->moduleKey) ?> => '<?= $generator->moduleName ?>',
    <?php foreach ($generator->getChildOperationKeys() as $key => $operationKey): ?>
        self::<?=$generator->getConstName($operationKey, true)?> => '<?=$generator->getChildOperationNames()[$key] . $generator->moduleName ?>',
    <?php endforeach;?>
    ];

        return $old + $new;
    }

    public static function initData()
    {
        $old = parent::iniData();

        $new = [
            [
                'id' => <?= $generator->moduleId ?>, 'name' => self::<?= $generator->getConstName($generator->moduleKey) ?>,
                'children' => [
        <?php foreach ($generator->getChildOperationKeys() as $key => $operationKey): ?>
            ['id' => <?= $generator->moduleId.($key+1) ?>, 'name' => self::<?=$generator->getConstName($operationKey, true)?>],
        <?php endforeach;?>
        ]
            ],
        ];

        return $old + $new;
    }
}
