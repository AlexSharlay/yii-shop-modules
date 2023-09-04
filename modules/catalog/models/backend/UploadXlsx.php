<?php

namespace common\modules\catalog\models\backend;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

class UploadXlsx extends Model
{

    /**
     * @var UploadedFile
     */
    public $xlsxFile;
    public $import_type;

    public $file;

    public $columns_name;
    public $brand_name;

    public function rules()
    {
        return [
            [['xlsxFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx'],
            [['import_type'], 'integer'],
            [['columns_name', 'brand_name'], 'string'],
            ['import_type', 'in', 'range' => [1, 2, 3]],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $path = FileHelper::normalizePath(Yii::getAlias('@statics/web/catalog/import/xlsx/'));
            $file = date("Y-m-d H-i-s") . '.' . $this->xlsxFile->extension;
            $this->file = $path.DIRECTORY_SEPARATOR.$file;

            $this->xlsxFile->saveAs($this->file);
            return true;
        } else {
            return false;
        }
    }

}