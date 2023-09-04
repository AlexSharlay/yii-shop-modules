<?php

namespace common\modules\catalog\models\frontend;

use Yii;

class ComplectRel extends \common\modules\catalog\models\ComplectRel
{

    /*
     * common\modules\catalog\models\frontend;
     * ->joinWith('complectRelChildren.elementChildren1')
     */

    public function getElementChildren1()
    {
        return $this->hasMany(Element::className(), ['id' => 'id_element_children'])->from(['ech4' => Element::tableName()]);;
    }

    /*
     * common\modules\catalog\models\frontend;
     * ->joinWith('complectRelParent.elementChildren2.complectRelChildren2.elementChildren3')
     */

    public function getElementChildren2()
    {
        return $this->hasMany(Element::className(), ['id' => 'id_element_parent'])->from(['ech5' => Element::tableName()]);;
    }

    /*
     * common\modules\catalog\models\frontend;
     * ->joinWith('complectRelParent.elementChildren2.complectRelChildren2.elementChildren3')
     */

    public function getElementChildren3()
    {
        return $this->hasMany(Element::className(), ['id' => 'id_element_children'])->from(['ech6' => Element::tableName()]);;
    }

}
