<?php

namespace common\modules\catalog\models\backend;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\catalog\models\Element;

/**
 * ElementSearch represents the model behind the search form about `common\modules\catalog\models\Element`.
 */
class ElementSearch extends Element
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        // @todo:
        return [
            [['id', 'id_category', 'id_manufacturer', 'id_measurement', 'price_1c', 'price', 'price_old', 'in_stock', 'is_defect', 'is_main', 'is_model', 'is_custom', 'hit', 'published'], 'integer'],
            [['alias', 'title', 'title_model',  'desc_mini', 'desc_full', 'desc_yml', 'code_1c', 'created_at', 'article', 'seo_title', 'seo_keyword', 'seo_desc'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        if ($params['fill'] == 1) {
            $query = Element::find()
                ->from('{{%catalog_element}} e')
                ->leftJoin('{{%catalog_field_element_value_rel}} fev', 'e.id = fev.id_element')
                ->where('fev.id IS NULL')
                ->groupBy('e.id');
        } else {
            $query = Element::find();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_category' => $this->id_category,
            'id_manufacturer' => $this->id_manufacturer,
            'id_measurement' => $this->id_measurement,
            'price_1c' => $this->price_1c,
            'code_1c' => $this->code_1c,
            'price' => $this->price,
            'price_old' => $this->price_old,
            'in_stock' => $this->in_stock,
            'is_defect' => $this->is_defect,
            'is_main' => $this->is_main,
            'is_model' => $this->is_model,
            'is_custom' => $this->is_custom,
            'hit' => $this->hit,
            'published' => $this->published,
        ]);

        $query->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'title_model', $this->title_model])
            ->andFilterWhere(['like', 'code_1c', $this->code_1c])
            ->andFilterWhere(['like', 'desc_mini', $this->desc_mini])
            ->andFilterWhere(['like', 'desc_full', $this->desc_full])
            ->andFilterWhere(['like', 'desc_yml', $this->desc_yml])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'seo_title', $this->seo_title])
            ->andFilterWhere(['like', 'seo_keyword', $this->seo_keyword])
            ->andFilterWhere(['like', 'seo_desc', $this->seo_desc]);

        return $dataProvider;
    }
}
