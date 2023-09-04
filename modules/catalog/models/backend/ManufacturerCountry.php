<?php

namespace common\modules\catalog\models\backend;

use Yii;

class ManufacturerCountry extends \common\modules\catalog\models\ManufacturerCountry
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturer()
    {
        return $this->hasOne(Manufacturer::className(), ['id' => 'id_manufacturer']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'id_country']);
    }
}
