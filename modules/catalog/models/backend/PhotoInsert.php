<?php

namespace common\modules\catalog\models\backend;
use yii\db\ActiveRecord;

use Yii;

/**
 * This is the model class for table "{{%catalog_photo}}".
 *
 * @property integer $id
 * @property integer $id_element
 * @property string $name
 * @property integer $sort
 * @property integer $is_cover
 */
class PhotoInsert extends \common\modules\catalog\models\Photo
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_photo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            /*
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['id_element', 'sort', 'is_cover'], 'integer'],
            [['id_element'], 'required'],
            */
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_element' => 'Id Element',
            'name' => 'Name',
            'sort' => 'Sort',
            'is_cover' => 'Is Cover',
        ];
    }
}
