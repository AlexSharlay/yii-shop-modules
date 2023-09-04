<?php

namespace common\modules\logger\models\backend;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\logger\models\backend\LoggerAction;

/**
 * LoggerActionSearch represents the model behind the search form about `common\modules\logger\models\backend\LoggerAction`.
 */
class LoggerActionSearch extends LoggerAction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_user'], 'integer'],
            [['module', 'controller', 'action', 'ip', 'create', 'headers', 'get', 'post'], 'safe'],
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
        $query = LoggerAction::find();

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
            'id_user' => $this->id_user,
        ]);

        $query
            ->andFilterWhere(['like', 'module', $this->module])
            ->andFilterWhere(['like', 'controller', $this->controller])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'create', $this->create])
            ->andFilterWhere(['like', 'headers', $this->headers])
            ->andFilterWhere(['like', 'get', $this->get])
            ->andFilterWhere(['like', 'post', $this->post])
            ->orderBy('create DESC');

        return $dataProvider;
    }
}
