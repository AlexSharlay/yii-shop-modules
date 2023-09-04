<?php

namespace common\modules\mods\mods_news\models\backend;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\mods\mods_news\models\backend\News;

/**
 * NewsSearch represents the model behind the search form about `common\modules\mods\mods_news\models\backend\News`.
 */
class NewsSearch extends News
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'col', 'row', 'ico_color', 'url_target', 'published'], 'integer'],
            [['title', 'ico_title', 'image', 'url'], 'safe'],
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
        $query = News::find();

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
            'col' => $this->col,
            'row' => $this->row,
            'ico_color' => $this->ico_color,
            'url_target' => $this->url_target,
            'published' => $this->published,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'ico_title', $this->ico_title])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}
