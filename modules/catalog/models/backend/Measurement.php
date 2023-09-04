<?php

namespace common\modules\catalog\models\backend;

use Yii;

/**
 * This is the model class for table "{{%catalog_measurement}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $ico
 */
class Measurement extends  \common\modules\catalog\models\Measurement
{

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
