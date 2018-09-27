<?php

namespace kriss\widgets;

use Yii;
use yii\base\Exception;
use yii\base\Widget;
use yii\data\BaseDataProvider;
use yii\grid\ActionColumn as YiiActionColumn;
use yii\grid\CheckboxColumn as YiiCheckboxColumn;
use yii\grid\RadioButtonColumn as YiiRadioButtonColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii2tech\csvgrid\CsvGrid;

/**
 * $export = ExportMenu::widget([
 * 'dataProvider' => $dataProvider,
 * 'columns' => ExportMenuHelper::transColumns($columns),
 * ]);
 *
 * @since 2.1.2
 */
class ExportMenu extends Widget
{
    public $dataProvider;

    public $columns = [];

    public $skipColumnClass = [
        YiiActionColumn::class, YiiCheckboxColumn::class, YiiRadioButtonColumn::class,
    ];

    public $batchSize = 200;

    public $csvGridConfig;

    public $savePath;

    public $sendName = 'export.csv';

    public $exportMenuLabel = '导出';

    public $exportMenuOptions = ['class' => 'btn btn-primary'];

    public $exportPostParam = 'export-menu-export';

    public $dataColumnClass = ExportMenuDataColumn::class;


    private $triggeredExport = false;

    public function init()
    {
        if (!class_exists('yii2tech\csvgrid\CsvGrid')) {
            throw new Exception('must install yii2tech/csv-grid');
        }
        parent::init();
        $this->triggeredExport = Yii::$app->request->post($this->exportPostParam, false);
    }

    public function run()
    {
        if (!$this->triggeredExport) {
            $this->renderExportMenu();
            return;
        }
        $this->export();
    }

    protected function renderExportMenu()
    {
        echo Html::beginForm();
        echo Html::hiddenInput($this->exportPostParam, true);
        echo Html::submitInput($this->exportMenuLabel, $this->exportMenuOptions);
        echo Html::endForm();
    }

    protected function export()
    {
        Yii::trace('Export Start', __CLASS__);

        $config = [
            'csvFileConfig' => [
                'writeBom' => true, // 解决utf8中文乱码问题
            ],
            'batchSize' => $this->batchSize,
            'columns' => $this->getColumns(),
        ];
        if ($this->csvGridConfig) {
            $config = ArrayHelper::merge($config, $this->csvGridConfig);
        }

        /** @var BaseDataProvider $dataProvider */
        $dataProvider = $this->dataProvider;
        if ($dataProvider->hasProperty('pagination')) {
            $dataProvider->pagination->pageSize = $config['batchSize'];
        }
        $config['dataProvider'] = $dataProvider;

        $exporter = new CsvGrid($config);
        $exportResult = $exporter->export();
        if ($this->savePath) {
            $exportResult->saveAs($this->savePath);
        } else {
            $exportResult->send($this->sendName);
        }

        Yii::trace('Export End', __CLASS__);
    }

    protected function getColumns()
    {
        $columns = [];
        foreach ($this->columns as $column) {
            if (is_array($column) && isset($column['class'])) {
                $skip = false;
                foreach ($this->skipColumnClass as $columnClass) {
                    if (is_a($column['class'], $columnClass, true)) {
                        $skip = true;
                        break;
                    }
                }
                if ($skip) {
                    continue;
                }
            } else {
                // 替换默认的 yii2tech\csvgrid\DataColumn
                if (is_string($column)) {
                    $column = [
                        'class' => $this->dataColumnClass,
                        'attribute' => $column,
                    ];
                } else {
                    $column['class'] = $this->dataColumnClass;
                }
            }
            $columns[] = $column;
        }
        return $columns;
    }
}
