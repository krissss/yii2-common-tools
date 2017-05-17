<?php

namespace kriss\widgets;

use dosamigos\ckeditor\CKEditor;
use yii\helpers\Url;

class SimpleCkEditor extends CKEditor
{
    /**
     * 图片上传目录
     * 填''为禁用
     * @var array|string
     */
    public $imageUploadUrl = ['upload/ck-image'];
    /**
     * 上传的图片浏览目录
     * 填''为禁用
     * @var array|string
     */
    public $imageBrowserUrl = ['upload/ck-image-browser'];
    /**
     * 使用的tools数
     * @var string
     */
    public $preset = 'custom';

    public function init()
    {
        $imageUploadUrl = $this->imageUploadUrl;
        if ($imageUploadUrl) {
            $imageUploadUrl = Url::to($this->imageUploadUrl);
        }

        $imageBrowserUrl = $this->imageBrowserUrl;
        if ($imageBrowserUrl) {
            $imageBrowserUrl = Url::to($this->imageBrowserUrl);
        }

        $this->clientOptions = [
            /** @link http://docs.ckeditor.com/#!/api/CKEDITOR.config */
            'language' => 'zh-CN',
            'filebrowserImageUploadUrl' => $imageUploadUrl,
            'filebrowserImageBrowseUrl' => $imageBrowserUrl,
            'toolbarCanCollapse' => true,
            'toolbarGroups' => [
                ['name' => 'document', 'groups' => ['mode', 'document', 'doctools']],
                ['name' => 'clipboard', 'groups' => ['clipboard', 'undo']],
                ['name' => 'editing', 'groups' => ['find', 'selection', 'spellchecker', 'editing']],
                ['name' => 'forms', 'groups' => ['forms']],
                ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
                ['name' => 'paragraph', 'groups' => ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']],
                ['name' => 'links', 'groups' => ['links']],
                ['name' => 'insert', 'groups' => ['insert']],
                ['name' => 'styles', 'groups' => ['styles']],
                ['name' => 'colors', 'groups' => ['colors']],
                ['name' => 'tools', 'groups' => ['tools']],
                ['name' => 'others', 'groups' => ['others']],
                ['name' => 'about', 'groups' => ['about']]
            ],
            'removeButtons' => 'Source,Save,Print,Templates,About,ShowBlocks,Flash,Language'
        ];
        parent::init();
    }
}