<?php

namespace common\modules\catalog\models\backend;

use common\modules\catalog\models\ManufacturerCountry;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%catalog_manufacturer}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $title_yml
 * @property string $desc
 * @property string $alias
 * @property string $ico
 * @property integer $sort
 * @property integer $use_model
 * @property integer $hide_filter_after
 * @property integer $published
 * @property string $seo_title
 * @property string $seo_keyword
 * @property string $seo_desc
 */
class Manufacturer extends \common\modules\catalog\models\Manufacturer
{

    /*
     * Для коллекций
     */

    public function getManufacturers()
    {
        return $this->hasOne(Category::className(), ['id' => 'id_manufacturer']);
    }

    public static function getManufacturersList()
    {
        $models = Manufacturer::find()->asArray()->all();
        return ArrayHelper::map($models, 'id', 'title');
    }

    /*
     *  Поиск, удаление, добавление производителям стран
     */

    public static function SearchCountry($str)
    {
        $search = Country::find()
            ->andWhere(['like', 'title', $str])
            ->select('id, title')->limit(20)->asArray()->all();
        return json_encode($search);
    }

    public static function AddCountry($id_manufacturer, $id_country)
    {
        $rel = new ManufacturerCountry();
        $rel->id_manufacturer = $id_manufacturer;
        $rel->id_country = $id_country;
        $rel->save();
    }

    public static function DeleteCountry($id_manufacturer, $id_country)
    {
        ManufacturerCountry::find()->andWhere(['id_manufacturer' => $id_manufacturer, 'id_country' => $id_country])->select('id')->one()->delete();
    }
}
