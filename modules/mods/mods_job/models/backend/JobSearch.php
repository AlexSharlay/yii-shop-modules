<?php

namespace common\modules\mods\mods_job\models\backend;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\mods\mods_job\models\backend\Job;

/**
 * JobSearch represents the model behind the search form about `common\modules\mods\mods_job\models\backend\Job`.
 */
class JobSearch extends Job
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sort'], 'integer'],
            [['department', 'vacancy', 'salary', 'content'], 'safe'],
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
        $query = Job::find()->orderBy('department, sort');

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
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'department', $this->department])
            ->andFilterWhere(['like', 'vacancy', $this->vacancy])
            ->andFilterWhere(['like', 'salary', $this->salary])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
