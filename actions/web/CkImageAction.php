<?php
/**
 * CKEditor 图片上传的操作
 * @link http://docs.ckeditor.com/#!/guide/dev_file_browser_api
 */
namespace kriss\actions\web;

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

    public function run()
    {
        $request = Yii::$app->request;
        $funcNum = $request->get('CKEditorFuncNum');
        $cKEditorName = $request->get('CKEditor');
        $model = new FileUpload([
            'maxSize' => $this->maxSize,
            'extensions' => $this->extensions,
        ]);
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