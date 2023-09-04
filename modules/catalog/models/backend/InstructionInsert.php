<?php

namespace common\modules\catalog\models\backend;
use yii\db\ActiveRecord;

use Yii;

/**
 * This is the model class for table "{{%catalog_instruction}}".
 *
 * @property integer $id
 * @property integer $id_element
 * @property string $name
 * @property integer $sort
 * @property integer $published
 */
class InstructionInsert extends \common\modules\catalog\models\Instruction
{

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

            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['id_element', 'sort', 'published'], 'integer'],
            [['id_element'], 'required'],

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
