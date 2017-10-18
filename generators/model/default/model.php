<?php
/* @var $this yii\web\View */
/* @var $generator kriss\generators\model\Generator */
/* @var $className string class name */
/* @var $daoClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use <?= $generator->daoNs . '\\' . $daoClassName ?>;

/**
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= $daoClassName . "\n" ?>
{
}
