<?php

namespace common\modules\catalog\models;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\modules\catalog\traits\ModuleTrait;
use common\components\fileapi\behaviors\UploadBehavior;

/**
 * This is the model class for table "{{%catalog_country}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $ico
 */
class Country extends  ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_country}}';
    }

    public function behaviors()
    {
        return [
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'ico' => [
                        'path' => $this->module->countryPath,
                        'tempPath' => $this->module->countryTempPath,
                        'url' => $this->module->countryUrl
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
            [['title'], 'required'],
            [['title'], 'string'],
            [['title', 'ico'], 'string', 'max' => 255]
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
            'ico' => 'Флаг',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturerCountries()
    {
        return $this->hasMany(ManufacturerCountry::className(), ['id_country' => 'id']);
    }

}
