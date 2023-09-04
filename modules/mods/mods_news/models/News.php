<?php

namespace common\modules\mods\mods_news\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\mods\mods_news\traits\ModuleTrait;

/**
 * This is the model class for table "{{%mods_news}}".
 *
 * @property integer $id
 * @property integer $col
 * @property integer $row
 * @property string $title
 * @property string $ico_title
 * @property integer $ico_color
 * @property string $image
 * @property string $url
 * @property integer $url_target
 * @property integer $published
 */
class News extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mods_news}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['col', 'row', 'ico_color', 'url_target', 'published'], 'integer'],
            [['image'], 'required'],
            [['title', 'ico_title', 'image', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'col' => 'Столбец',
            'row' => 'Строка',
            'title' => 'Заголовок',
            'ico_title' => 'Заметка',
            'ico_color' => 'Цвет заметки',
            'image' => 'Картинка',
            'url' => 'Url',
            'url_target' => 'Открыть в новом окне',
            'published' => 'Опубликовано',
        ];
    }
}
