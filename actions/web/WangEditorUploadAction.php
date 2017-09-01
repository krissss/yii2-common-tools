<?php
/**
 * wangEditor 上传操作
 * @link https://www.kancloud.cn/wangfupeng/wangeditor3/335782
 */
namespace kriss\actions\web;

use kriss\models\FileUpload;
use kriss\tools\Fun;
use Yii;
use yii\base\Action;
use yii\base\InvalidValueException;
use yii\web\Response;

class WangEditorUploadAction extends Action
{
    /**
     * 文件上传接收的类，必须是 kriss\models\FileUpload 或它的子类
     * @var string|array
     */
    public $fileUploadClass = [
        'class' => 'kriss\models\FileUpload',
        'maxSize' => 5242880, // 5M
        'extensions' => 'jpg,png'
    ];

    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $name = urldecode($request->post('name'));
        $prefix = urldecode($request->post('prefix'));
        $fileUploadClass = $this->fileUploadClass;
        $model = Yii::createObject($fileUploadClass);
        if(!$model instanceof FileUpload){
            throw new InvalidValueException('fileUploadClass must be instance of kriss\models\FileUpload');
        }
        $model->multi = true;
        if ($fileNames = $model->upload($name, $prefix)) {
            return [
                'errno' => 0,
                'data' => $fileNames
            ];
        }
        return [
            'error' => Fun::formatModelErrors2String($model->errors)
        ];
    }
}