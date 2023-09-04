<?php

namespace common\modules\catalog\models\backend;

use Yii;
use common\modules\catalog\models\backend\Element;
use common\modules\catalog\models\backend\Collection;

/**
 * This is the model class for table "{{%catalog_collection_rel}}".
 *
 * @property integer $id
 * @property integer $id_collection
 * @property integer $id_element
 */
class CollectionRel extends \common\modules\catalog\models\CollectionRel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_collection_rel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_collection', 'id_element'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_collection' => 'Id Collection',
            'id_element' => 'Id Element',
        ];
    }

    public function getElement()
    {
        return $this->hasOne(Element::className(), ['id' => 'id_element']);
    }

    public function getCollection()
    {
        return $this->hasOne(Collection::className(), ['id' => 'id_collection']);
    }
}
