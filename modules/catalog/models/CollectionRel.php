<?php

namespace common\modules\catalog\models;

use Yii;
use common\modules\catalog\traits\ModuleTrait;

/**
 * This is the model class for table "{{%catalog_collection_rel}}".
 *
 * @property integer $id
 * @property integer $id_collection
 * @property integer $id_element
 */
class CollectionRel extends \yii\db\ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_collection_rel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_collection', 'id_element'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_collection' => 'Id Collection',
            'id_element' => 'Id Element',
        ];
    }
}
