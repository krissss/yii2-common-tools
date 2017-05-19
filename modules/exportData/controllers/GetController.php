<?php

namespace kriss\modules\exportData\controllers;

use kriss\modules\exportData\models\ExportDatabase;
use kriss\modules\exportData\Module;
use Yii;
use yii\base\Controller;

class GetController extends Controller
{
    public function actionIndex() {
        $exportDatabase = new ExportDatabase([
            'exportDataNameSpace' => Module::getInstance()->exportDataNameSpace,
            'exportData' => Module::getInstance()->exportData,
            'csvSuffix' => Module::getInstance()->csvSuffix,
            'zipSuffix' => Module::getInstance()->zipSuffix,
        ]);
        $filename = $exportDatabase->getExportFile();
        if (!file_exists($filename)) {
            if ($exportDatabase->checkZipLoading()) {
                return "<h1>正在生成文件，请稍后发起请求。</h1>";
            } else {
                // 此处可能由于时间处理太久导致超时，如果可以，一般需要做异步处理，PHP下暂无太好的实现
                ini_set("max_execution_time", 30000);
                $exportDatabase->generateZip();
            }
        }
        return Yii::$app->response->sendFile($filename);
    }
}