<?php

namespace common\modules\mods\mods_slides\models\backend;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\mods\mods_slides\models\backend\Slides;

/**
 * SlidesSearch represents the model behind the search form about `common\modules\mods\mods_slides\models\backend\Slides`.
 */
class SlidesSearch extends Slides
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'published'], 'integer'],
            [['img', 'url'], 'safe'],
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
        $query = Slides::find();

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
            'name' => $this->name,
            'url' => $this->url,
            'published' => $this->published,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'img', $this->img])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}
