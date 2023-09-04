<?php

namespace common\modules\shop\models;

use Yii;
use common\modules\blogs\traits\ModuleTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%shop_user_city}}".
 *
 * @property integer $id
 * @property string $region
 * @property string $day
 * @property string $city
 */
class UserCity extends ActiveRecord
{

    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_user_city}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['region', 'day', 'city'], 'required'],
            [['region'], 'string'],
            [['day', 'city'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region' => 'Область',
            'day' => 'День',
            'city' => 'Город',
        ];
    }

    public static function getCities() {

        $cities = UserCity::find()->orderBy('region ASC, city ASC')->asArray()->all();
        $citiesNew = [];
        foreach($cities as $city) {
            $citiesNew[$city['region']][$city['id']] = $city['city'];
        }
        return $citiesNew;
    }
}
