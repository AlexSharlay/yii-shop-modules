<?php

namespace common\modules\catalog\models;

use Yii;
use common\modules\catalog\traits\ModuleTrait;

/**
 * This is the model class for table "{{%catalog_collection}}".
 *
 * @property integer $id
 * @property string $alias
 */
class Collection extends \yii\db\ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_collection}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias'], 'required'],
            [['alias'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'alias' => 'Alias',
        ];
    }
}
