<?php

namespace kriss\widgets;

use kriss\wangEditor\WangEditorWidget;
use yii\helpers\Html;

class SimpleWangEditor extends WangEditorWidget
{
    /**
     * @var string
     */
    public $uploadUrl = '/upload/wang-editor';

    public function init()
    {
        parent::init();
        if ($this->hasModel()) {
            $prefix = Html::getInputId($this->model, $this->attribute);
        } else {
            $prefix = $this->name;
        }
        // @link https://www.kancloud.cn/wangfupeng/wangeditor3/335777
        $menu = json_encode([
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
        ]);
        $this->clientJs = <<<JS
{name}.customConfig.uploadImgServer = '{$this->uploadUrl}';
{name}.customConfig.uploadFileName = 'filename[]';
{name}.customConfig.uploadImgParams = {
    name: 'filename',
    prefix: '{$prefix}'
};
{name}.customConfig.menus = {$menu};
JS;
    }
}