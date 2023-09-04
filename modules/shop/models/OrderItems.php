<?php
/**
 * Created by PhpStorm.
 * User: vitalbu
 * Date: 11.05.2017
 * Time: 15:04
 */

namespace common\modules\shop\models;


use yii\db\ActiveRecord;

//use Yii;

/**
 * This is the model class for table "{{%shop_order_items}}".
 *
 * @property string $id
 * @property string $order_id
 * @property string $product_id
 * @property string $article
 * @property string $title
 * @property double $price
 * @property integer $qty_item
 * @property double $sum_item
 */
class OrderItems extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_order_items}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'article', 'title', 'price', 'qty_item', 'sum_item'], 'required'],
            [['order_id', 'product_id', 'qty_item'], 'integer'],
            [['price', 'sum_item'], 'number'],
            [['article', 'title'], 'string', 'max' => 255],
        ];
    }
}
