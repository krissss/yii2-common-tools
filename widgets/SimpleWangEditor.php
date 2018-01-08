<?php

namespace kriss\widgets;

use kriss\wangEditor\WangEditorWidget;
use yii\helpers\Html;
use yii\helpers\Url;

class SimpleWangEditor extends WangEditorWidget
{
    /**
     * 上传文件路径
     * 为 false 时将使用 Url::to(['/upload/wang-editor'])
     * 否则使用给定的地址。
     * action 可以 kriss\actions\web\WangEditorUploadAction
     * @var string|false
     */
    public $uploadUrl = false;

    /**
     * 上传文件大小：默认5M
     * @var int
     */
    public $uploadImgMaxSize = 5242880;

    /**
     * 一次上传文件数量
     * @var int
     */
    public $uploadImgMaxLength = 10;

    public function init()
    {
        parent::init();

        if ($this->uploadUrl === false) {
            $this->uploadUrl = Url::to(['/upload/wang-editor']);
        }

        $this->setClientJs();
    }

    /**
     * @return string
     */
    protected function getPrefix()
    {
        return $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->name;
    }

    /**
     * @link https://www.kancloud.cn/wangfupeng/wangeditor3/335777
     * @return array
     */
    protected function getMenu()
    {
        return [
            'head',  // 标题
            'bold',  // 粗体
            'italic',  // 斜体
            'underline',  // 下划线
            'strikeThrough',  // 删除线
            'foreColor',  // 文字颜色
            'backColor',  // 背景颜色
            'link',  // 插入链接
            'list',  // 列表
            'justify',  // 对齐方式
            'quote',  // 引用
            //'emoticon',  // 表情
            'image',  // 插入图片
            'table',  // 表格
            'video',  // 插入视频
            'code',  // 插入代码
            'undo',  // 撤销
            'redo'  // 重复
        ];
    }

    protected function setClientJs()
    {
        $menu = json_encode($this->getMenu());
        $prefix = $this->getPrefix();
        $this->clientJs = <<<JS
{name}.customConfig.uploadImgServer = '{$this->uploadUrl}';
{name}.customConfig.uploadFileName = 'filename[]';
{name}.customConfig.uploadImgMaxSize = {$this->uploadImgMaxSize};
{name}.customConfig.uploadImgMaxLength = {$this->uploadImgMaxLength};
{name}.customConfig.uploadImgParams = {
    name: 'filename',
    prefix: '{$prefix}'
};
{name}.customConfig.uploadImgHooks = {
    fail: function (xhr, editor, result) {
        editor.hasAlerted = true
        alert('图片上传失败:' + result.error)
    }
}
{name}.customConfig.customAlert = function (info) {
    if(!{name}.hasAlerted){
        alert(info);
    }else{
        {name}.hasAlerted = false;
    }
};
{name}.customConfig.menus = {$menu};
JS;
    }
}