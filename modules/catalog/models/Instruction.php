<?php

namespace common\modules\catalog\models;
use Yii;
use yii\db\ActiveRecord;
use common\modules\catalog\traits\ModuleTrait;


/**
 * This is the model class for table "{{%catalog_instruction}}".
 *
 * @property integer $id
 * @property integer $id_element
 * @property string $name
 * @property integer $sort
 * @property integer $published
 */
class Instruction extends ActiveRecord
{

    use ModuleTrait;

    public $file = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_instruction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif'],
            [['file'], 'file', 'maxFiles' => 100],
            [['id_element', 'sort', 'published'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
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
            'published' => 'Публикация',
        ];
    }
}
