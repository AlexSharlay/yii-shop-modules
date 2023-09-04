<?php

namespace common\modules\catalog\models\backend;

use common\modules\catalog\models\Manufacturer;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%catalog_collection}}".
 *
 * @property integer $id
 * @property string $alias
 */
class Collection extends \common\modules\catalog\models\Collection
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog_collection}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias'], 'required'],
            [['alias'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Alias',
        ];
    }

    public function getCategories()
    {
        return $this->hasOne(Category::className(), ['id' => 'id_category']);
    }

    public static function getCategoriesList()
    {
        $models = Category::find()->asArray()->all();
        return ArrayHelper::map($models, 'id', 'title');
    }


    public function getManufacturers()
    {
        return $this->hasOne(Manufacturer::className(), ['id' => 'id_manufacturer']);
    }

    public static function getManufacturersList()
    {
        $models = Manufacturer::find()->asArray()->all();
        return ArrayHelper::map($models, 'id', 'title');
    }


    public function getElements()
    {
        return $this->hasOne(Element::className(), ['id' => 'id_element']);
    }

    public static function getElementsList()
    {
        $models = Element::find()->asArray()->all();
        return ArrayHelper::map($models, 'id', 'title');
    }


    public function getCollectionRels()
    {
        return $this->hasMany(CollectionRel::className(), ['id_collection' => 'id']);
    }

    public static function ElementInCollection($id) {
        $model = Collection::find()->andWhere(['id' => $id])->with(['collectionRels', 'collectionRels.element'])->asArray()->all();
        $arr = [];
        foreach ($model['0']['collectionRels'] as $row) {
            $arr[] = $row['element'];
        }
        return $arr;
    }

    public static function AddElementToCollection($id_collection, $id_element) {
        $rel = new CollectionRel();
        $rel->id_collection = $id_collection;
        $rel->id_element = $id_element;
        $rel->save();
    }

    public static function DeleteElementFromCollection($id_collection, $id_element) {
        CollectionRel::find()->andWhere(['id_element' => $id_element, 'id_collection' => $id_collection])->select('id')->one()->delete();
    }


    public static function SearchElements($str) {
        $search = Element::find()->andWhere(['like', 'title', $str])->orWhere(['like', 'article', $str])->select('id, title, article, price')->limit(20)->asArray()->all();
        return json_encode($search);
    }
}
