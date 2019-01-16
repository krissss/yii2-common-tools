<?php

namespace kriss\widgets;

use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use kriss\traits\KrissTranslationTrait;
use Yii;
use yii\base\Exception;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class SimpleDynaGrid extends Widget
{
    use KrissTranslationTrait;

    /**
     * 组件 id
     * 如果需要使用 dynaGrid 的存储，则必须单独定义，否则 key 可能一样
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
     * @var bool
     */
    public $isExportAll = false;

    /**
     * 导出菜单，必须是 kriss\widgets\ExportMenu 的子类
     * @var string
     */
    public $exportMenuClass;

    /**
     * ExportMenu 的配置
     * @var array
     */
    public $exportMenuConfig = [];

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
     * 刷新 Url，默认为当前控制器的 action
     * @var array
     */
    public $toolbarRefreshUrl;

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
    public $storage = 'session';

    /**
     * DataColumn 的 class 名
     * 必须是 yii\grid\DataColumn 的子类
     * @var string
     */
    public $dataColumnClass = DataColumn::class;

    /**
     * gridView 的 pager 字段
     * @var array
     */
    public $gridPager;

    /**
     * 其他额外的 DynaGrid 配置
     * @link http://demos.krajee.com/dynagrid#dynagrid
     * @var array
     */
    public $otherConfig;

    /**
     * @var string
     */
    private $_pjaxContainerId;

    public function init()
    {
        $this->initKrissI18N();
        if (!isset($this->dynaGridId)) {
            $this->dynaGridId = $this->id;
        }
        if (!isset($this->columns)) {
            throw new Exception('必须设置 columns');
        }
        if (!isset($this->dataProvider)) {
            throw new Exception('必须设置 dataProvider');
        }
        if (!$this->toolbarRefreshUrl) {
            $this->toolbarRefreshUrl = [Yii::$app->controller->action->id];
        }

        if (!$this->gridPager) {
            $this->gridPager = [
                'class' => LinkPagerWithSubmit::class,
                'firstPageLabel' => Yii::t('kriss', '第一页'),
                'lastPageLabel' => Yii::t('kriss', '最后一页'),
            ];
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

        if ($this->isExportAll && !$this->exportMenuClass) {
            $this->exportMenuClass = ExportMenu::class;
        }

        $this->_pjaxContainerId = 'pjax-' . rand(1000, 9999);
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
                'responsive' => true,
                'responsiveWrap' => false, // 小480的屏幕自适应去除
                'striped' => false,
                'bordered' => false, // responsive 为 true 时，bordered 为 true, 使用 filter 时，会导致出现水平滚动条，原因未知
                'hover' => true,
                'floatHeader' => $this->floatHeader,
                'floatHeaderOptions' => [],
                'pjax' => $this->enablePjax,
                'pjaxSettings' => [
                    'options' => [
                        'id' => $this->_pjaxContainerId,
                        'enablePushState' => true,
                    ],
                ],
                'dataColumnClass' => $this->dataColumnClass,
                'panel' => [
                    'heading' => false,
                    'before' => $this->showTotalSummary ? '{summary}' : '',
                    'after' => '{pager}',
                    'afterOptions' => ['class' => 'text-center'],
                    'footer' => false,
                    'summaryOptions' => ['class' => ''],
                ],
                'pager' => $this->gridPager,
                'export' => [
                    'showConfirmAlert' => false,
                    'target' => GridView::TARGET_BLANK,
                ],
                'exportConfig' => [
                    GridView::EXCEL => true,
                ],
            ],
        ];

        if (isset($this->searchModel)) {
            $config['gridOptions']['filterModel'] = $this->searchModel;
        }

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
            if (isset($this->exportMenuConfig['columns'])) {
                $exportMenuColumns = $this->exportMenuConfig['columns'];
                unset($this->exportMenuConfig['columns']);
            } else {
                $exportMenuColumns = $this->columns;
            }
            $fullExportMenu = ExportMenu::widget(ArrayHelper::merge([
                'dataProvider' => $this->dataProvider,
                'columns' => $exportMenuColumns,
            ], $this->exportMenuConfig));
            array_push($toolbar, [
                'content' => $fullExportMenu,
            ]);
        }
        if ($this->toolbarRefresh === true) {
            array_push($toolbar, [
                'content' => Html::a($this->toolbarRefreshLabel, $this->toolbarRefreshUrl, [
                    'class' => $this->toolbarRefreshClass,
                ]),
            ]);
        }
        $config['gridOptions']['toolbar'] = $toolbar;

        if ($this->otherConfig) {
            return ArrayHelper::merge($this->otherConfig, $config);
        }
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

    public function run()
    {
        $this->renderDynaGrid();
    }
}
