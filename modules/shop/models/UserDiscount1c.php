<?php

namespace common\modules\shop\models;
use common\modules\blogs\traits\ModuleTrait;
use common\modules\catalog\models\Category1c;
use yii\db\ActiveRecord;
//use common\modules\catalog\models\backend\Category;
use yii\helpers\ArrayHelper;

use Yii;

/**
 * This is the model class for table "{{%shop_user_discount1c}}".
 *
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_category
 * @property integer $discount
 */
class UserDiscount1c extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_user_discount1c}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_category', 'discount'], 'required'],
            [['id_user', 'id_category', 'discount'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'id_category' => 'Id Category',
            'discount' => 'Discount',
        ];
    }

    public static function getCategoriesAll()
    {
        return Category1c::find()
            ->select('id, id_parent, title')
            ->from('{{%catalog_category1c}}')
            ->asArray()->all();
    }

    public static function getCategories()
    {
        return Category1c::find()
            ->select('c1.id, c1.id_parent, c1.title')
            ->from('{{%catalog_category1c}} c1')
            ->leftJoin('{{%catalog_category1c}} c2', 'c1.id = c2.id_parent')
            ->where('c2.id IS NULL')
            ->asArray()->all();
    }

    public static function getUserDiscount1c($id_user)
    {
        $models = Category1c::find()
            ->select('id_category as id, discount')
            ->from('{{%shop_user_discount1c}}')
            ->where('id_user = :id_user',[':id_user' => $id_user])
            ->asArray()->all();
        return ArrayHelper::map($models, 'id', 'discount');
    }

    public static function discounts($id_user)
    {
        $categoriesAll = self::getCategoriesAll();
        $categories = self::getCategories();
        $discounts = self::getUserDiscount1c($id_user);

        $arr = [];
        $i = 0;
        $count = 0;

        foreach($categories as $category) {
            $arr[$i]['id'] = $category['id'];
            $arr[$i]['discount'] = 0;

            foreach($categoriesAll as $c) {
                if ($c['id'] == $category['id_parent']) {
                    $arr[$i]['title'] = $c['title'].' - '.$category['title'];
                    break;
                }
            }

            foreach($discounts as $discount_key => $discount) {
                if ($category['id'] == $discount_key) {
                    $arr[$i]['discount'] = $discount;
                    if ($discount) $count++;
                    break;
                }
            }
            $i++;
        }

        $arr['discounts'] = $arr;
        $arr['count'] = $count;

        return $arr;
    }
}
