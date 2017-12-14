<?php

namespace kriss\modules\exportData\models;

use Yii;
use yii\base\Component;
use yii\db\ActiveRecord;
use yii\db\Exception;
use ZipArchive;

class ExportDatabase extends Component
{
    /**
     * ActiveRecord Class to export namespace
     * like '\common\models'
     * @var string
     */
    public $exportDataNameSpace;
    /**
     * ActiveRecord Class Full Name
     * if $exportTableClassNameSpace is null
     * this should be class with namespace
     * otherwise it can be Only Class Name under $exportTableClassNameSpace
     * @var array
     */
    public $exportData = [];
    /**
     * @var string
     */
    public $csvSuffix = '.csv';
    /**
     * @var string
     */
    public $zipSuffix = '.data';

    private $fileSavePath;

    public function init()
    {
        parent::init();
        if (Yii::getAlias('@common', false)) {
            $path = Yii::getAlias('@common');
        } else {
            $path = Yii::getAlias('@app');
        }
        $this->fileSavePath = $path . '/runtime/data/';
        if (!is_dir($this->fileSavePath)) {
            mkdir($this->fileSavePath);
        }

        if (count($this->exportData) == 0) {
            throw new Exception('must set exportData');
        }
        if ($this->exportDataNameSpace) {
            foreach ($this->exportData as &$item) {
                $item = $this->exportDataNameSpace . '\\' . $item;
            }
        }
    }

    /**
     * 检查是否有 zip 文件正在处理生成
     * @return bool
     */
    public function checkZipLoading()
    {
        $zipLoadingFile = $this->fileSavePath . $this->getZipLoadingName();
        if (file_exists($zipLoadingFile)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取用于导出的 zip 文件
     * @return string
     */
    public function getExportFile()
    {
        return $this->fileSavePath . $this->getZipFileName();
    }

    /**
     * 生成 zip 压缩包
     */
    public function generateZip()
    {
        // 先生成正在生成 zip 文件的标志文件
        $zipLoadingFile = $this->fileSavePath . $this->getZipLoadingName();
        $fp = fopen($zipLoadingFile, 'w');
        fwrite($fp, 'zip file loading....');
        fclose($fp);

        $csvFileArr = [];

        foreach ($this->exportData as $item) {
            $csvFile = $this->fileSavePath . $this->getCsvFileName($item);
            $this->generateCsv($item, $csvFile);
            $csvFileArr[] = $csvFile;
        }

        $zipFile = $this->fileSavePath . $this->getZipFileName();
        $zip = new ZipArchive (); // 使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释,php7自动开启
        if ($zip->open($zipFile, ZipArchive::CREATE) !== TRUE) {
            exit ('无法打开文件，或者文件创建失败');
        }
        foreach ($csvFileArr as $val) {
            $zip->addFile($val, basename($val)); // 第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
        }
        $zip->close();

        // 删除所有的 csv 文件
        foreach ($csvFileArr as $val) {
            unlink($val);
        }

        // 删除正在生成 zip 文件的标志文件
        unlink($zipLoadingFile);
    }

    /**
     * 生成 csv 文件
     * @param $object ActiveRecord 需要生成 csv 文件的对象
     * @param $csvFile string csv文件最终存放的地址，绝对路径
     */
    protected function generateCsv($object, $csvFile)
    {
        $fp = fopen($csvFile, 'w');
        // 获取全部列名
        $model = new $object();
        $head = array_keys($model->attributes);
        foreach ($head as $i => $v) {
            // CSV的Excel支持GBK编码，一定要转换，否则乱码，//ignore为忽略不可转码的字符
            $head[$i] = iconv('utf-8', 'gbk//ignore', $v);
        }
        // 写入列名
        fputcsv($fp, $head);
        // 分批次写入数据
        $limit = 10000;
        foreach ($object::find()->asArray()->batch($limit) as $i => $models) {
            // 逐行取出数据，不浪费内存
            foreach ($models as $model) {
                $row = array_values($model);
                foreach ($row as $i => $v) {
                    $row[$i] = iconv('utf-8', 'gbk//ignore', $v);
                }
                fputcsv($fp, $row);
                unset($row);
            }
        }
        fclose($fp);
    }

    /**
     * 获取 csv 文件名
     * @param $object ActiveRecord 需要生成 csv 文件的对象
     * @return string User20160624115012.csv
     */
    protected function getCsvFileName($object)
    {
        $nameArr = explode('\\', $object);
        return end($nameArr) . date('YmdHis') . $this->csvSuffix;
    }

    /**
     * 获取 zip 文件名
     * @return string 2016062420e45b0c5da4a2f6.data
     */
    protected function getZipFileName()
    {
        return date('YmdHis') . substr(md5(date('YmdHis')), 8, 16) . $this->zipSuffix;
    }

    /**
     * 获取表示正在生成zip文件的文件名
     * @return string
     */
    protected function getZipLoadingName()
    {
        return 'loading-' . $this->getZipFileName();
    }
}