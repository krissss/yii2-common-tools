<?php
/** @var $constArr array */
/** @var $msgArr array */
/** @var $initDataArr array */
/** @var $generator \kriss\modules\auth\console\controllers\AuthGeneratorController */

echo "<?php\n";
?>

namespace <?= $generator->getClassNamespace($generator->genClass) ?>;

class <?= $generator->getClassName($generator->genClass) ?> extends <?= $generator->baseClass . "\n" ?>
{
    const CAN_PERMISSION_PERMISSION = <?= $generator->canPermissionPermission ? 'true' : 'false' ?>;
    const PERMISSION_ID = <?= $generator->permissionId ?>;
    const ROLE_ID = <?= $generator->roleId ?>;

<?php foreach ($constArr as $const): ?>
    <?= $const . "\n" ?>
<?php endforeach; ?>

    public static function getMessageData()
    {
        $old = parent::getMessageData();

        $new = [
<?php foreach ($msgArr as $msg): ?>
            <?= $msg . "\n" ?>
<?php endforeach; ?>
        ];

        return $old + $new;
    }

    public static function initData()
    {
        $old = parent::initData();

        $new = [
<?php foreach ($initDataArr as $initData): ?>
            [
                'id' => <?= $initData['id'] ?>, 'name' => <?= $initData['name'] ?>,
                'children' => [
<?php foreach ($initData['children'] as $item): ?>
                    ['id' => <?= $item['id'] ?>, 'name' => <?= $item['name'] ?>],
<?php endforeach; ?>
                ],
            ],
<?php endforeach; ?>
        ];

        return array_merge($old, $new);
    }
}

