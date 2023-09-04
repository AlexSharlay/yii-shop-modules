<?php

namespace common\modules\mods\mods_review\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\mods\mods_review\traits\ModuleTrait;

/**
 * This is the model class for table "{{%mods_review}}".
 *
 * @property integer $id
 * @property integer $mark
 * @property string $name
 * @property string $city
 * @property string $desc
 * @property string $date
 * @property integer $published
 */
class Review extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mods_review}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mark', 'name', 'desc', 'date', 'published'], 'required'],
            [['mark', 'published'], 'integer'],
            [['name', 'city', 'date'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mark' => 'Оценка',
            'name' => 'Имя',
            'city' => 'Город',
            'desc' => 'Отзыв',
            'date' => 'Дата',
            'published' => 'Статус',
        ];
    }
}
