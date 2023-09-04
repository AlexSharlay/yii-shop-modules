<?php
/**
 * Created by PhpStorm.
 * User: vitalbu
 * Date: 23.11.2017
 * Time: 10:09
 */

namespace common\modules\shop\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%shop_how}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $desc
 * @property integer $sort
 * @property boolean $published
 *
 * @property ShopHow[] $shopHow
 */
class How extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_how}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['sort', 'published'], 'integer'],
            [['title','desc'], 'string', 'max' => 255]
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
            'published' => 'Публикация',
        ];
    }

}
