<?php

namespace common\modules\catalog\models\backend;

use Yii;
use common\modules\catalog\models\backend\Element;

/**
 * This is the model class for table "{{%catalog_complect_rel}}".
 *
 * @property integer $id
 * @property integer $id_element_parent
 * @property integer $id_element_children
 */
class ComplectRel extends \common\modules\catalog\models\ComplectRel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_complect_rel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_element_parent', 'id_element_children'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_element_parent' => 'Id Element Parent',
            'id_element_children' => 'Id Element Children',
        ];
    }

    public function getElementChildren()
    {
        return $this->hasMany(Element::className(), ['id' => 'id_element_children'])->from(['ec' => Element::tableName()]);;
    }

    public static function ElementInComplect($id)
    {
        $model = ComplectRel::find()->andWhere(['id_element_parent' => $id])->with(['elementChildren'])->asArray()->all();
        $arr = [];
        foreach ($model as $rel) {
            $arr['elements'][] = $rel['elementChildren']['0'];
        }
        $arr['id_parent'] = $id;
        return $arr;
    }

    public function SearchElementsForComplect($str)
    {
        $search = Element::find()
            //->andWhere(['=','is_complect',0])
            ->andWhere(['or', ['like', 'title', $str], ['like', 'article', $str],])
            ->select('id, title, article, price')->limit(20)->asArray()->all();
        return json_encode($search);
    }

    public function AddToComplect($id_parent, $id_child)
    {
        $rel = new ComplectRel();
        $rel->id_element_parent = $id_parent;
        $rel->id_element_children = $id_child;
        $rel->save();
    }

    public function DeleteFromComplect($id_parent, $id_child)
    {
        ComplectRel::find()->andWhere(['id_element_parent' => $id_parent, 'id_element_children' => $id_child])->select('id')->one()->delete();
    }


}
