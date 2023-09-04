<?php

namespace common\modules\catalog\models\backend;

use Yii;
use common\modules\catalog\models\backend\Element;

/**
 * This is the model class for table "{{%catalog_model_rel}}".
 *
 * @property integer $id
 * @property integer $id_element_parent
 * @property integer $id_element_children
 */
class ModelRel extends \common\modules\catalog\models\ModelRel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_model_rel}}';
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

    public function getElementParent()
    {
        return $this->hasMany(\common\modules\catalog\models\frontend\Element::className(), ['id' => 'id_element_children'])->from(['epa' => \common\modules\catalog\models\frontend\Element::tableName()]);
    }

    public function getElementChildren()
    {
        return $this->hasMany(\common\modules\catalog\models\frontend\Element::className(), ['id' => 'id_element_parent'])->from(['ech' => \common\modules\catalog\models\frontend\Element::tableName()]);;
    }

    public function getElementChildren2()
    {
        return $this->hasMany(\common\modules\catalog\models\frontend\Element::className(), ['id' => 'id_element_parent'])->from(['ech2' => \common\modules\catalog\models\frontend\Element::tableName()]);;
    }

    public function getElementChildren3()
    {
        return $this->hasMany(\common\modules\catalog\models\frontend\Element::className(), ['id' => 'id_element_parent'])->from(['ech3' => \common\modules\catalog\models\frontend\Element::tableName()]);;
    }

    public function ElementInModel($id)
    {
        $model = ModelRel::find()
            ->select('mr.id_element_parent, mr.id_element_children, e.title, e.id, e.article, e.price')
            ->from('{{%catalog_model_rel}} mr')
            ->leftJoin('{{%catalog_element}} e',' e.id = mr.id_element_children')
            ->where('mr.id_element_parent = :id', [':id' => $id])
            ->asArray()->all();

        $arr = [];
        $arr['elements'] = $model;
        $arr['id_parent'] = $id;
        return $arr;
    }

    public function SearchElementsForModel($str)
    {
        $search = Element::find()
            ->andWhere(['=','is_model',1])
            ->andWhere(['or', ['like', 'title', $str], ['like', 'article', $str],])
            ->select('id, title, article, price')->limit(20)->asArray()->all();
        return json_encode($search);
    }

    public function AddToModel($id_parent, $id_child)
    {
        $rel = new ModelRel();
        $rel->id_element_parent = $id_parent;
        $rel->id_element_children = $id_child;
        $rel->save();
    }

    public function DeleteFromModel($id_parent, $id_child)
    {
        ModelRel::find()->andWhere(['id_element_parent' => $id_parent, 'id_element_children' => $id_child])->select('id')->one()->delete();
    }


}
