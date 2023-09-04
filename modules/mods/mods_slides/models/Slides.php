<?php

namespace common\modules\mods\mods_slides\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\mods\mods_slides\traits\ModuleTrait;

/**
 * This is the model class for table "{{%mods_slides}}".
 *
 * @property integer $id
 * @property string $img
 * @property string $discount
 * @property string $name
 * @property string $content
 * @property string $url
 * @property integer $sort
 * @property integer $published
 * @property string $created_at
 * @property string $updated_at
 */
class Slides extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mods_slides}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort', 'published'], 'integer'],
//            [['img'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['discount'], 'string', 'max' => 10],
            [['img', 'name', 'content', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'img' => 'Картинка',
            'url' => 'Url',
            'sort' => 'Сортировка',
            'published' => 'Опубликовано',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }
}
