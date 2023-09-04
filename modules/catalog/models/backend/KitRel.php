<?php

namespace common\modules\catalog\models\backend;

use Yii;

class KitRel extends \common\modules\catalog\models\KitRel
{

    public function getElementChildren()
    {
        return $this->hasMany(Element::className(), ['id' => 'id_element_children'])->from(['kitrel1' => Element::tableName()]);;
    }

    public function getKits($id)
    {
        $kit = KitRel::find()->andWhere(['id_element_parent' => $id])->with(['elementChildren'])->orderBy(['id_kit' => SORT_ASC, 'sort' => SORT_ASC])->asArray()->all();
        $arr = [];
        foreach ($kit as $rel) {
            $arr[] = array_merge($rel['elementChildren']['0'], ['id_kit'=>$rel['id'], 'num_kit'=>$rel['id_kit'], 'sort'=>$rel['sort']]);
        }
        return json_encode($arr);
    }

    public function SearchElementsForKit($str)
    {
        $search = Element::find()
            ->andWhere(['or', ['like', 'title', $str], ['like', 'article', $str],])
            ->select('id, title, article, price')->limit(20)->asArray()->all();
        return json_encode($search);
    }

    public function AddToKit($id_element_parent, $id_element_children, $id_kit)
    {
        $sort = KitRel::find()->andWhere(['id_kit' => $id_kit,'id_element_parent' => $id_element_parent])->select('id')->max('sort') + 1;
        $rel = new KitRel();
        $rel->id_element_parent = $id_element_parent;
        $rel->id_element_children = $id_element_children;
        $rel->id_kit = $id_kit;
        $rel->sort = $sort;
        return $rel->save();
    }

    public function DeleteFromKit($id_kit)
    {
        return KitRel::find()->andWhere(['id' => $id_kit])->select('id')->one()->delete();
    }

}
