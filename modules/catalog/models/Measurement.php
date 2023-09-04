<?php

namespace common\modules\catalog\models;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\modules\catalog\traits\ModuleTrait;

/**
 * This is the model class for table "{{%catalog_measurement}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $ico
 */
class Measurement extends  ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_measurement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'code'], 'string'],
            [['title', 'code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'code' => 'Код',
        ];
    }


}
