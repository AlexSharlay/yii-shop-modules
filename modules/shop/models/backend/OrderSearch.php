<?php

namespace common\modules\shop\models\backend;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\shop\models\backend\Order;

/**
 * OrderSearch represents the model behind the search form about `common\modules\shop\models\backend\Order`.
 */
class OrderSearch extends Order
{

    public $ynp;
    public $firmName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_user', 'created', 'status', 'cost'], 'integer'],
            [['data', 'one_data', 'ynp', 'firmName'], 'safe'],
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

        $role = Yii::$app->user->identity->role;

        $query = Order::find()
            ->alias('so')
            ->joinWith([
                'profile' => function ($q) {
                    $q->from('{{%profiles}} u');
                },
            ]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $dataProvider->sort->attributes['ynp'] = [
            'asc' => ['u.ynp' => SORT_ASC],
            'desc' => ['u.ynp' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['firmName'] = [
            'asc' => ['u.firmName' => SORT_ASC],
            'desc' => ['u.firmName' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'so.id' => $this->id,
            'so.id_user' => $this->id_user,
            'so.created' => $this->created,
            'so.status' => $this->status,
            'so.cost' => $this->cost,
        ]);

        $query->andFilterWhere(['like', 'so.data', $this->data])
            ->andFilterWhere(['like', 'so.one_data', $this->one_data])
            ->andFilterWhere(['like', 'u.ynp', $this->ynp])
            ->andFilterWhere(['like', 'u.firmName', $this->firmName]);

        if (!in_array($role, ['admin','superManager'])) {
            $query->andWhere('so.id_manager = :id_manager', [':id_manager' => Yii::$app->user->id]);
        }

        return $dataProvider;
    }
}
