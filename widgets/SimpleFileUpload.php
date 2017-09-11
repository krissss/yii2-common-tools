<?php

namespace kriss\widgets;

use kartik\base\InputWidget;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

class SimpleFileUpload extends InputWidget
{
    /**
     * 批量
     * @var bool
     */
    public $multi = true;

    /**
     * 初始数据，可以是url数组
     * @var array
     */
    public $initPreviewData = [];

    /**
     * 最大文件数
     * @var int
     */
    public $maxFileCount = 5;

    /**
     * 最大文件大小
     * @var int
     */
    public $maxFileSize = 5120;

    /**
     * 上传文件的前缀
     * 默认false，将使用 hiddenInput 的 id
     * @var string|false
     */
    public $uploadPrefix = false;

    /**
     * 上传文件的路径
     * @var array
     */
    public $uploadUrl = ['/upload/images'];

    /**
     * 删除文件的路径
     * @var array
     */
    public $deleteUrl = ['/upload/delete'];

    /**
     * 上传文件接收的 mimeType, 此处定义为
     * @link https://www.sitepoint.com/mime-types-complete-list/
     * @var string
     */
    public $uploadAcceptMime = 'image/*';

    /**
     * 插件允许的文件类型
     * @link http://plugins.krajee.com/file-input#option-allowedfiletypes
     * @var array|null
     */
    public $allowedFileTypes = ['image'];

    /**
     * 插件允许的文件后缀
     * @link http://plugins.krajee.com/file-input#option-allowedfileextensions
     * @var array|null
     */
    public $allowedFileExtensions = ['jpg', 'png'];


    private $_hiddenInputName;
    private $_uploadName;

    public function init()
    {
        parent::init();
        if ($this->hasModel()) {
            $this->_hiddenInputName = Html::getInputName($this->model, $this->attribute);
            if ($this->uploadPrefix === false) {
                $this->uploadPrefix = Html::getInputId($this->model, $this->attribute);
            }
        } else {
            $this->_hiddenInputName = $this->name;
            if ($this->uploadPrefix === false) {
                $this->uploadPrefix = $this->name;
            }
        }
        $this->_uploadName = $this->id . $this->_hiddenInputName;
        if ($this->multi) {
            $this->_hiddenInputName .= '[]';
        }
    }

    public function run()
    {
        parent::run();
        $this->renderHiddenInput();
        $this->renderUploadFile();
    }

    /**
     * @return string
     */
    protected function getPrefix()
    {
        return $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->name;
    }

    /**
     * 渲染隐藏域
     */
    protected function renderHiddenInput()
    {
        for ($i = 0; $i < $this->maxFileCount; $i++) {
            echo Html::hiddenInput($this->_hiddenInputName, isset($this->initPreviewData[$i]) ? $this->initPreviewData[$i] : '');
        }
    }

    /**
     * 渲染上传文件的视图
     */
    protected function renderUploadFile()
    {
        $uploadName = $this->_uploadName;
        $photoUrlHiddenName = $this->_hiddenInputName;

        // 点击上传，追加到隐藏域中
        $fileUploadedJs = <<<JS
function (event, data, previewId, index) {
    var filename = data.response.filenames[0];
    var hiddenInput = $('[name="$photoUrlHiddenName"][value=""]').eq(0);
    if(hiddenInput.length>0){
        hiddenInput.val(filename);
        hiddenInput.attr('data-preview-id', previewId);
    }else{
        alert('上传文件数超过限制，将不能保存成功。当前最大上传文件个数：' + {$this->maxFileCount});
    }
}
JS;
        // 初始预览的文件，点击删除，把隐藏域中的删掉
        $fileDeletedJs = <<<JS
function (event, key, jqXHR, data) {
    $('[name="$photoUrlHiddenName"]').eq(key).val('');
}
JS;
        // 本次上传成功后的文件，点击删除，把隐藏域中的删掉
        $fileSuccessRemoveJs = <<<JS
function (event, id) {
    $('[data-preview-id="'+id+'"]').val('')
}
JS;

        $initialPreview = false;
        $initialPreviewConfig = [];
        if ($this->initPreviewData && is_array($this->initPreviewData)) {
            foreach ($this->initPreviewData as $index => $previewUrl) {
                $initialPreview[] = $previewUrl;
                $initialPreviewConfig[] = [
                    'key' => $index,
                    'width' => '160px'
                ];
            }
        }

        echo FileInput::widget([
            'name' => $uploadName,
            'options' => [
                'accept' => $this->uploadAcceptMime,
                'multiple' => $this->multi
            ],
            'pluginOptions' => [
                'uploadAsync' => true,
                'uploadUrl' => Url::to($this->uploadUrl),
                'deleteUrl' => Url::to($this->deleteUrl),
                'uploadExtraData' => [
                    'name' => $uploadName,
                    'prefix' => $this->getPrefix(),
                ],
                'allowedFileTypes' => $this->allowedFileTypes,
                'allowedFileExtensions' => $this->allowedFileExtensions,
                'maxFileSize' => $this->maxFileSize,
                'maxFileCount' => $this->maxFileCount,
                'initialPreview' => $initialPreview,
                'initialPreviewAsData' => true,
                'initialPreviewShowDelete' => true,
                'initialPreviewConfig' => $initialPreviewConfig,
                'overwriteInitial' => false,
            ],
            'pluginEvents' => [
                'fileuploaded' => new JsExpression($fileUploadedJs),
                'filedeleted' => new JsExpression($fileDeletedJs),
                'filesuccessremove' => new JsExpression($fileSuccessRemoveJs),
            ]
        ]);
    }
}