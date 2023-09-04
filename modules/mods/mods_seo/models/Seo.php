<?php

namespace common\modules\mods\mods_seo\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\mods\mods_worker\traits\ModuleTrait;

/**
 * This is the model class for table "{{%mods_seo}}".
 *
 * @property integer $id
 * @property string $url
 * @property string $note
 * @property string $seo_title
 * @property string $seo_keyword
 * @property string $seo_desc
 */
class Seo extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mods_seo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['url', 'note', 'seo_title', 'seo_keyword', 'seo_desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'note' => 'Заметка',
            'seo_title' => 'Seo Title',
            'seo_keyword' => 'Seo Keyword',
            'seo_desc' => 'Seo Desc',
        ];
    }
}
