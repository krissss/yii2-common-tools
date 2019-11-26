<?php

namespace kriss\widgets;

use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
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
 * 'columns' => $columns,
 * ]);
 *
 * or
 *
 * $export = new ExportMenu([
 * 'dataProvider' => $dataProvider,
 * 'columns' => $columns,
 * ]);
 * $export->doExport();
 *
 * @since 2.1.2
 */
class ExportMenu extends Widget
{
    public $dataProvider;

    public $columns = [];

    public $skipColumnClass = [];

    public $batchSize = 200;

    public $csvGridConfig;

    public $savePath;

    public $sendName;

    public $exportMenuLabel;

    public $exportMenuOptions = ['class' => 'btn btn-primary'];

    public $exportPostParam = 'export-menu-export';

    public $dataColumnClass = ExportMenuDataColumn::class;
    /**
     * @var string|array
     */
    public $exportMenuHelperClass = ExportMenuHelper::class;


    private $triggeredExport = false;

    public function init()
    {
        if (!$this->sendName) {
            $this->sendName = 'export' . date('YmdHis') . '.csv';
        }
        if (!isset($this->exportMenuLabel)) {
            $this->exportMenuLabel = Yii::t('kriss', '导出');
        }
        $this->skipColumnClass = array_merge($this->skipColumnClass, [
            YiiActionColumn::class, YiiCheckboxColumn::class, YiiRadioButtonColumn::class,
        ]);

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

    /**
     * export at once
     * @since 2.1.5
     */
    public function doExport()
    {
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
        Yii::debug('Export Start', __CLASS__);

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

        Yii::debug('Export End', __CLASS__);
    }

    protected function getColumns()
    {
        $this->transColumns();
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
                // visible 为 false 时跳过
                if (isset($column['visible']) && !$column['visible']) {
                    $skip = true;
                }
                if ($skip) {
                    continue;
                }
            } else {
                // 替换默认的 yii2tech\csvgrid\DataColumn
                if (is_string($column)) {
                    $column = $this->createDataColumn($column);
                } else {
                    $column['class'] = $this->dataColumnClass;
                }
            }
            $columns[] = $column;
        }
        return $columns;
    }

    protected function transColumns()
    {
        /** @var ExportMenuHelper $helper */
        if (is_string($this->exportMenuHelperClass)) {
            $this->exportMenuHelperClass = [
                'class' => $this->exportMenuHelperClass,
            ];
        }
        $helper = Yii::createObject(array_merge($this->exportMenuHelperClass, [
            'columns' => $this->columns,
        ]));
        $this->columns = $helper->trans();
    }

    /**
     * @param $text
     * @return array
     * @throws InvalidConfigException
     * @see CsvGrid::createDataColumn()
     */
    protected function createDataColumn($text)
    {
        if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new InvalidConfigException('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
        }

        return [
            'class' => $this->dataColumnClass,
            'attribute' => $matches[1],
            'format' => isset($matches[3]) ? $matches[3] : 'raw',
            'label' => isset($matches[5]) ? $matches[5] : null,
        ];
    }
}
