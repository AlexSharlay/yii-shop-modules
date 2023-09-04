<?php

namespace common\modules\catalog\models;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\modules\catalog\traits\ModuleTrait;
use common\components\fileapi\behaviors\UploadBehavior;
use common\modules\catalog\models\backend\ManufacturerCountry;

/**
 * This is the model class for table "{{%catalog_manufacturer}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $desc
 * @property string $alias
 * @property string $ico
 * @property integer $published
 * @property integer $perekup
 * @property string $seo_title
 * @property string $seo_keyword
 * @property string $seo_desc
 */
class Manufacturer extends  ActiveRecord
{

    use ModuleTrait;

    /** Unpublished status **/
    const STATUS_UNPUBLISHED = 0;
    /** Published status **/
    const STATUS_PUBLISHED = 1;

    /** Unperekup status **/
    const STATUS_UNPEREKUP = 0;
    /** Perekup status **/
    const STATUS_PEREKUP = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_manufacturer}}';
    }

    public function behaviors()
    {
        return [
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'ico' => [
                        'path' => $this->module->manufacturerPath,
                        'tempPath' => $this->module->manufacturerTempPath,
                        'url' => $this->module->manufacturerUrl
                    ],
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'alias'], 'required'],
            [['alias'], 'unique'],
            [['desc'], 'string'],
            [['published', 'perekup'], 'integer'],
            [['title', 'alias', 'ico', 'seo_title', 'seo_keyword', 'seo_desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'desc' => 'Описание',
            'alias' => 'Alias',
            'published' => 'Публикация',
            'perekup' => 'Перекупная позиция',
            'ico' => 'Ico',
            'seo_title' => 'Seo Title',
            'seo_keyword' => 'Seo Keyword',
            'seo_desc' => 'Seo Desc',
        ];
    }

    public static function getPublishedArray()
    {
        return [
            self::STATUS_PUBLISHED => 'Опубликован',
            self::STATUS_UNPUBLISHED => 'Не опубликован',
        ];
    }

    public static function getPerekupArray()
    {
        return [
            self::STATUS_PEREKUP => 'Перекупная позиция',
            self::STATUS_UNPEREKUP => 'Не перекупная позиция',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturerCountries()
    {
        return $this->hasMany(ManufacturerCountry::className(), ['id_manufacturer' => 'id']);
    }

}
