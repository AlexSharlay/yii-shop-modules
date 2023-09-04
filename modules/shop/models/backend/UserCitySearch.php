<?php

namespace common\modules\shop\models\backend;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\shop\models\backend\UserCity;

/**
 * UserCitySearch represents the model behind the search form about `common\modules\shop\models\backend\UserCity`.
 */
class UserCitySearch extends UserCity
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['region', 'day', 'city'], 'safe'],
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
        $query = UserCity::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 200,
            ],
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
        ]);

        $query->andFilterWhere(['like', 'region', $this->region])
            ->andFilterWhere(['like', 'day', $this->day])
            ->andFilterWhere(['like', 'city', $this->city]);

        return $dataProvider;
    }
}
