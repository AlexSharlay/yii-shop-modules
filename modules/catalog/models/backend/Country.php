<?php

namespace common\modules\catalog\models\backend;

use common\modules\catalog\models\backend\ManufacturerCountry;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%catalog_country}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $ico
 */
class Country extends  \common\modules\catalog\models\Country
{

    /*
    public function getManufacturers()
    {
        return $this->hasOne(Category::className(), ['id' => 'id_manufacturer']);
    }

    public function getManufacturersList()
    {
        $models = Manufacturer::find()->asArray()->all();
        return ArrayHelper::map($models, 'id', 'title');
    }
    */

    public function getCountriesList()
    {
        $models = Country::find()->asArray()->all();
        return $models;
    }

    public function getCountriesListByManufacturerId($id)
    {
        $model = ManufacturerCountry::find()->andWhere(['id_manufacturer' => $id])->with(['country'])->asArray()->all();
        $arr = [];
        foreach ($model as $rel) {
            $arr[] = [
                'id' => $rel['country']['id'],
                'title' => $rel['country']['title']
            ];
        }
        return $arr;
    }

    public function SearchCountry($str)
    {
        $search = Country::find()
            ->andWhere(['like', 'title', $str])
            ->select('id, title')->limit(20)->asArray()->all();
        return json_encode($search);
    }

    public function AddCountry($id_manufacturer, $id_country)
    {
        $rel = new ManufacturerCountry();
        $rel->id_manufacturer = $id_manufacturer;
        $rel->id_country = $id_country;
        $rel->save();
    }

    public function DeletePayment($id_manufacturer, $id_country)
    {
        DeliveryPayment::find()->andWhere(['id_manufacturer' => $id_manufacturer, 'id_country' => $id_country])->select('id')->one()->delete();
    }

}
