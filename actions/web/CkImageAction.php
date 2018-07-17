<?php

namespace kriss\actions\web;

use kriss\models\FileUpload;
use kriss\tools\Fun;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;

/**
 * CKEditor 图片上传的操作
 * @link http://docs.ckeditor.com/#!/guide/dev_file_browser_api
 */
class CkImageAction extends Action
{
    /**
     * 文件上传接收的类，必须是 kriss\models\FileUpload 或它的子类
     * @var string|array
     */
    public $fileUploadClass = [
        'class' => 'kriss\models\FileUpload',
        'maxSize' => 5242880, // 5M
        'extensions' => 'jpg,png',
    ];

    public function run()
    {
        $request = Yii::$app->request;
        $funcNum = $request->get('CKEditorFuncNum');
        $cKEditorName = $request->get('CKEditor');
        $fileUploadClass = $this->fileUploadClass;
        $model = Yii::createObject($fileUploadClass);
        if (!$model instanceof FileUpload) {
            throw new InvalidConfigException('fileUploadClass must be instance of kriss\models\FileUpload');
        }
        if ($fileName = $model->upload('upload', $cKEditorName)) {
            $url = $fileName;
            $message = '上传成功';
            echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
            exit;
        } else {
            $error = Fun::getFirstError($model->errors);
            echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '', '$error');</script>";
            exit;
        }
    }
}
