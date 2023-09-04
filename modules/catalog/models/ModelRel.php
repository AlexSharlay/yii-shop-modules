<?php

namespace common\modules\catalog\models;

use Yii;
use common\modules\catalog\traits\ModuleTrait;

/**
 * This is the model class for table "{{%catalog_model_rel}}".
 *
 * @property integer $id
 * @property integer $id_element_parent
 * @property integer $id_element_children
 */
class ModelRel extends \yii\db\ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_model_rel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_element_parent', 'id_element_children'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_element_parent' => 'Id Element Parent',
            'id_element_children' => 'Id Element Children',
        ];
    }
}
