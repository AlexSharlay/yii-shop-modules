<?php

namespace common\modules\shop\models;
use common\modules\blogs\traits\ModuleTrait;
use yii\db\ActiveRecord;

use Yii;

/**
 * This is the model class for table "{{%shop_payment}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $desc
 * @property integer $sort
 *
 * @property ShopDeliveryPayment[] $shopDeliveryPayments
 */
class Payment extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['desc'], 'string'],
            [['sort'], 'integer'],
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
            'title' => 'Заголовок',
            'desc' => 'Описание',
            'sort' => 'Порядок',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryPayments()
    {
        return $this->hasMany(DeliveryPayment::className(), ['id_payment' => 'id']);
    }
}
