<?php

namespace common\modules\catalog\models\frontend;

use Yii;

class KitRel extends \common\modules\catalog\models\KitRel
{

    public function getElementChildren1()
    {
        return $this->hasMany(Element::className(), ['id' => 'id_element_children'])->from(['kitrel1' => Element::tableName()]);;
    }

}
