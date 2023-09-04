<?php

namespace common\modules\mods\mods_review\models\backend;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\mods\mods_review\models\backend\Review;

/**
 * ReviewSearch represents the model behind the search form about `common\modules\mods\mods_review\models\backend\Review`.
 */
class ReviewSearch extends Review
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mark', 'date', 'published'], 'integer'],
            [['name', 'city', 'desc'], 'safe'],
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
        $query = Review::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'mark' => $this->mark,
            'date' => $this->date,
            'published' => $this->published,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }
}
