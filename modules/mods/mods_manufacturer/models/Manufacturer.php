<?php

namespace common\modules\mods\mods_manufacturer\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\mods\mods_manufacturer\traits\ModuleTrait;

/**
 * This is the model class for table "{{%news_block}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $ico
 * @property integer $sort
 */
class Manufacturer extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mods_manufacturer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'sort'], 'required'],
            [['sort'], 'integer'],
            [['title', 'url', 'ico'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Текст',
            'url' => 'url',
            'ico' => 'Иконка',
            'sort' => 'Сортировка',
        ];
    }
}
