<?php
/**
 * CKEditor 图片上传的操作
 * @link http://docs.ckeditor.com/#!/guide/dev_file_browser_api
 */
namespace kriss\actions\web;

use Codeception\Exception\ConfigurationException;
use kriss\models\FileUpload;
use yii\base\Action;
use Yii;
use kriss\tools\Fun;

class CkImageAction extends Action
{
    /**
     * 默认5M
     * @var int
     */
    public $maxSize = 5242880;
    /**
     * 允许的扩展名
     * @var string
     */
    public $extensions = 'jpg,png';
    /**
     * 文件上传接收的类，必须是 kriss\models\FileUpload 或它的子类
     * @var string
     */
    public $fileUploadClass = 'kriss\models\FileUpload';

    public function run()
    {
        $request = Yii::$app->request;
        $funcNum = $request->get('CKEditorFuncNum');
        $cKEditorName = $request->get('CKEditor');
        $fileUploadClass = $this->fileUploadClass;
        $model = new $fileUploadClass([
            'maxSize' => $this->maxSize,
            'extensions' => $this->extensions,
        ]);
        if(!$model instanceof FileUpload){
            throw new ConfigurationException('fileUploadClass must be instance of kriss\models\FileUpload');
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