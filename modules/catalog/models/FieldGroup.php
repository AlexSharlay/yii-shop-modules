<?php

namespace common\modules\catalog\models;

use Yii;
use yii\db\ActiveRecord;
use common\modules\catalog\traits\ModuleTrait;

/**
 * This is the model class for table "{{%catalog_field_group}}".
 *
 * @property integer $id
 * @property integer $id_category
 * @property string $title
 * @property integer $sort
 */
class FieldGroup extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_field_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_category', 'sort'], 'integer'],
            [['title', 'sort'], 'required'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_category' => 'Id Category',
            'title' => 'Title',
            'sort' => 'Sort',
        ];
    }
}
