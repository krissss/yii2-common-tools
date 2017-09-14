<?php
/* @var $this yii\web\View */
/* @var $generator \kriss\generators\dynagrid\Generator */

use yii\helpers\StringHelper;

$useClasses = $generator->getSearchModelUseClasses();
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
$rules = $generator->generateSearchRules();
$searchConditions = $generator->generateSearchConditions();
echo "<?php\n";
?>

namespace <?= $generator->getClassNamespace($generator->searchModelClass) ?>;

<?php foreach ($useClasses as $useClass): ?>
use <?=$useClass?>;
<?php endforeach; ?>

class <?= $searchModelClass ?> extends <?= $modelClass . "\n" ?>
{
    public function rules()
    {
        return [
            <?= implode(",\n            ", $rules) ?>,
        ];
    }

    public function search($params)
    {
        $query = <?= isset($modelAlias) ? $modelAlias : $modelClass ?>::find();

        $dataProvider = new <?=$generator->getClassName($generator->activeDataProviderClass)?>([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
<?=in_array('created_at', $generator->getColumnNames()) ? "                    'created_at' => SORT_DESC,\n" : ''?>
<?=in_array('id', $generator->getColumnNames()) ? "                    'id' => SORT_DESC,\n" : ''?>
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        <?= implode("\n        ", $searchConditions) ?>

        return $dataProvider;
    }
}
