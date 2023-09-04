<?php

namespace common\modules\catalog\models\frontend;

use Yii;

class ModelRel extends \common\modules\catalog\models\ModelRel
{

    /*
     * common\modules\catalog\models\frontend;
     * ->joinWith('modelRelChildren.elementChildren')
     */

    public function getElementChildren1()
    {
        return $this->hasMany(Element::className(), ['id' => 'id_element_children'])->from(['ech1' => Element::tableName()]);;
    }

    /*
     * common\modules\catalog\models\frontend;
     * ->joinWith('modelRelParent.elementChildren2.modelRelChildren2.elementChildren3')
     */

    public function getElementChildren2()
    {
        return $this->hasMany(Element::className(), ['id' => 'id_element_parent'])->from(['ech2' => Element::tableName()]);;
    }

    /*
     * common\modules\catalog\models\frontend;
     * ->joinWith('modelRelParent.elementChildren2.modelRelChildren2.elementChildren3')
     */

    public function getElementChildren3()
    {
        return $this->hasMany(Element::className(), ['id' => 'id_element_children'])->from(['ech3' => Element::tableName()]);;
    }

}
