<?php
/**
 * @var $this yii\web\View
 * @var $generator \kriss\generators\crud\Generator
 */

$useClasses = $generator->getSearchModelUseClasses();
$rules = $generator->generateSearchRules();
$searchConditions = $generator->generateSearchConditions();
echo "<?php\n";
?>

namespace <?= $generator->getClassNamespace($generator->getSearchClass()) ?>;

<?php foreach ($useClasses as $useClass): ?>
use <?=$useClass?>;
<?php endforeach; ?>

class <?= $generator->getClassName($generator->getSearchClass()) ?> extends <?= $generator->modelName . "\n" ?>
{
    public function rules()
    {
        return [
            <?= implode(",\n            ", $rules) ?>,
        ];
    }

    public function search($params)
    {
        $query = <?= $generator->modelName ?>::find();

        $dataProvider = new ActiveDataProvider([
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
