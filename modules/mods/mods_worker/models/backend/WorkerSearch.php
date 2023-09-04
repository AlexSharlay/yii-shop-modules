<?php

namespace common\modules\mods\mods_worker\models\backend;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\mods\mods_worker\models\backend\Worker;

/**
 * WorkerSearch represents the model behind the search form about `common\modules\mods\mods_worker\models\backend\Worker`.
 */
class WorkerSearch extends Worker
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'department', 'sort'], 'integer'],
            [['fio', 'photo', 'flag1', 'flag2', 'flag3', 'flag4', 'flag5', 'position', 'phone', 'phone_mobile', 'email'], 'safe'],
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
        $query = Worker::find();

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
            'department' => $this->department,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'fio', $this->fio])
            ->andFilterWhere(['like', 'flag1', $this->flag1])
            ->andFilterWhere(['like', 'flag2', $this->flag2])
            ->andFilterWhere(['like', 'flag3', $this->flag3])
            ->andFilterWhere(['like', 'flag4', $this->flag4])
            ->andFilterWhere(['like', 'flag5', $this->flag5])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'phone_mobile', $this->phone_mobile])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
