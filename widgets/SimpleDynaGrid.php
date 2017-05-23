<?php
/**
 * 简单的列表页显示组件
 * 使用\kartik\dynagrid\DynaGrid
 * 包含配置等
 */

namespace kriss\widgets;

use kartik\dynagrid\DynaGrid;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\base\Exception;
use yii\base\Object;
use yii\helpers\Html;

class SimpleDynaGrid extends Object
{
    /**
     * 必配,id
     * @var string
     */
    public $dynaGridId;

    /**
     * 必配，显示的列
     * @var array
     */
    public $columns;

    /**
     * 必配
     * @var
     */
    public $dataProvider;

    /**
     * 选配
     * 如果配置则会出现filter，否则无
     * @var
     */
    public $searchModel;

    /**
     * 额外的toolbar
     * @var array
     */
    public $extraToolbar;

    /**
     * 是否允许 pjax
     * @var bool
     */
    public $enablePjax = false;

    /**
     * 是否需要个性化按钮
     * @var bool
     */
    public $isDynagrid = false;

    /**
     * 是否需要导出按钮
     * @var bool
     */
    public $isExport = false;

    /**
     * 是否需要导出全部按钮
     * 注：pjax 会导致导出全部失效
     * @var bool
     */
    public $isExportAll = false;

    /**
     * 是否需要显示所有的按钮
     * @var bool
     */
    public $isToggleData = false;

    /**
     * 是否显示单页统计
     * @var bool
     */
    public $showPageSummary = false;

    /**
     * 是否允许设置分页
     * isDynagrid 为 true 时有效
     * @var bool
     */
    public $allowPageSetting = true;

    /**
     * 是否显示总数统计
     * @var bool
     */
    public $showTotalSummary = true;

    /**
     * 表头固定
     * @var bool
     */
    public $floatHeader = false;

    /**
     * toolbar 刷新按钮
     * @var bool
     */
    public $toolbarRefresh = true;

    /**
     * 刷新 label
     * @var string
     */
    public $toolbarRefreshLabel = '<i class="glyphicon glyphicon-repeat"></i>';

    /**
     * 刷新 Url
     * @var array
     */
    public $toolbarRefreshUrl = ['index'];

    /**
     * 刷新 Class
     * @var array
     */
    public $toolbarRefreshClass = 'btn btn-default';

    /**
     * 个性化存储对象
     * @link http://demos.krajee.com/dynagrid#module
     * @var string
     */
    public $storage = 'cookie';

    /**
     * @var string
     */
    private $_pjaxContainerId;

    public function init()
    {
        if (!isset($this->dynaGridId)) {
            throw new Exception('必须设置 dynaGridId');
        }
        if (!isset($this->columns)) {
            throw new Exception('必须设置 columns');
        }
        if (!isset($this->dataProvider)) {
            throw new Exception('必须设置 dataProvider');
        }

        if (isset($this->extraToolbar)) {
            if (!is_array($this->extraToolbar)) {
                throw new Exception('extraToolbar 必须是一个数组');
            } else {
                if (!isset($this->extraToolbar[0]) || !is_array($this->extraToolbar[0])) {
                    throw new Exception('extraToolbar 必须是一个二维数组');
                }
            }
        }

        $this->_pjaxContainerId = 'pjax-' . rand(1000, 9999);

        foreach ($this->columns as &$column) {
            if (!is_array($column)) {
                $column = [
                    'class' => DataColumn::className(),
                    'attribute' => $column,
                ];
            } else {
                if (!isset($column['class'])) {
                    $column['class'] = DataColumn::className();
                }
            }
        }
    }

    /**
     * 获取配置信息
     * 为以后做扩展用
     * @return array
     */
    protected function getConfig()
    {
        $config = [
            'columns' => $this->columns,
            'options' => ['id' => $this->dynaGridId],
            'storage' => $this->storage,
            'allowFilterSetting' => false,
            'allowSortSetting' => false,
            'allowThemeSetting' => false,
            'allowPageSetting' => $this->isDynagrid ? $this->allowPageSetting : false,
            'gridOptions' => [
                'id' => 'grid',
                'dataProvider' => $this->dataProvider,
                'showPageSummary' => $this->showPageSummary,
                'responsiveWrap' => false, // 小480的屏幕自适应去除
                'striped' => false,
                'hover' => true,
                'floatHeader' => $this->floatHeader,
                'floatHeaderOptions' => [],
                'pjax' => $this->enablePjax, // Pjax 模式下 Export All 有个bug，pjax 更新后点击不能导出数据
                'pjaxSettings' => [
                    'options' => [
                        'id' => $this->_pjaxContainerId,
                        'enablePushState' => true
                    ],
                ],
                'panel' => [
                    'heading' => false,
                    'before' => $this->showTotalSummary ? '{summary}' : '',
                    'after' => '{pager}',
                    'afterOptions' => ['class' => 'text-center'],
                    'footer' => false
                ],
                'pager' => [
                    'class' => LinkPagerWithJump::className(),
                    'firstPageLabel' => '第一页',
                    'lastPageLabel' => '最后一页',
                ],
                'export' => [
                    'showConfirmAlert' => false,
                    'target' => GridView::TARGET_BLANK,
                ],
                'exportConfig' => [
                    GridView::EXCEL => true,
                ]
            ],
        ];

        if (isset($this->searchModel)) {
            $config['gridOptions']['filterModel'] = $this->searchModel;
        }

        // 导出全部去除隐藏的列
        $fullExportMenuColumns = $this->columns;
        foreach ($fullExportMenuColumns as $key => &$column) {
            if (isset($column['visible'])) {
                unset($column['visible']);
            }
            // 去除操作栏
            if (in_array($column['class'], ['\yii\grid\ActionColumn', '\kartik\grid\ActionColumn'])) {
                unset($fullExportMenuColumns[$key]);
            }
        }
        $fullExportMenu = ExportMenu::widget([
            'dataProvider' => $this->dataProvider,
            'columns' => $fullExportMenuColumns,
            'target' => ExportMenu::TARGET_BLANK,
            'pjaxContainerId' => $this->_pjaxContainerId,
            'clearBuffers' => true,
            'exportConfig' => [
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_CSV => false,
                ExportMenu::FORMAT_PDF => false,
                ExportMenu::FORMAT_TEXT => false,
            ],
            'dropdownOptions' => [
                'label' => '导出全部',
                'class' => 'btn btn-default',
            ],
        ]);

        $toolbar = [];
        if (isset($this->extraToolbar)) {
            $toolbar = array_merge($toolbar, $this->extraToolbar);
        }
        if ($this->isDynagrid === true) {
            array_push($toolbar, '{dynagrid}');
        }
        if ($this->isToggleData === true) {
            array_push($toolbar, '{toggleData}');
        }
        if ($this->isExport === true) {
            array_push($toolbar, '{export}');
        }
        if ($this->isExportAll === true) {
            array_push($toolbar, $fullExportMenu);
        }
        if ($this->toolbarRefresh === true) {
            array_push($toolbar, [
                'content' => Html::a($this->toolbarRefreshLabel, $this->toolbarRefreshUrl, [
                    'class' => $this->toolbarRefreshClass
                ]),
            ]);
        }
        $config['gridOptions']['toolbar'] = $toolbar;

        return $config;
    }

    /**
     * 生成列表
     * @throws \Exception
     */
    public function renderDynaGrid()
    {
        $config = $this->getConfig();
        echo DynaGrid::widget($config);
    }

}