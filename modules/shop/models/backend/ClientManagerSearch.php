<?php

namespace common\modules\shop\models\backend;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\shop\models\backend\ClientManager;

/**
 * ClientManagerSearch represents the model behind the search form about `common\modules\shop\models\backend\ClientManager`.
 */
class ClientManagerSearch extends ClientManager
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_manager', 'active'], 'integer'],
            [['id_client'], 'string'],
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
        $query = ClientManager::find();

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
            'id_manager' => $this->id_manager,
            //'id_client' => $this->id_client,
            'active' => $this->active,
        ]);

        if ($this->id_client) {
            $query
                ->innerJoin('tbl_profiles p', 'id_client = p.user_id')
                ->where(
                    '(p.name LIKE :name OR p.patronymic LIKE :patronymic OR p.surname LIKE :surname OR p.firmName LIKE :firmName)',
                    [
                        ':name' => '%'.$this->id_client.'%',
                        ':patronymic' => '%'.$this->id_client.'%',
                        ':surname' => '%'.$this->id_client.'%',
                        ':firmName' => '%'.$this->id_client.'%',
                    ]
                );
        }

        return $dataProvider;
    }
}
