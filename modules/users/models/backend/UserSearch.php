<?php

namespace common\modules\users\models\backend;

use common\modules\users\models\Profile;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * User search model.
 */
class UserSearch extends User
{
    /**
     * @var string Name
     */
    public $name;

    /**
     * @var string Surname
     */
    public $surname;

    public $ynp;

    public $firmName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // String
            [['name', 'surname', 'ynp', 'firmName', 'username', 'email'], 'string'],
            // Role
            ['role', 'in', 'range' => array_keys(self::getRoleArray())],
            // Status
            ['status_id', 'in', 'range' => array_keys(self::getStatusArray())],
            // Date
            [['created_at', 'updated_at'], 'date', 'format' => 'd.m.Y']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params Search params
     *
     * @return ActiveDataProvider DataProvider
     */
    public function search($params)
    {
        $query = self::find()->joinWith(['profile']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $dataProvider->sort->attributes['name'] = [
            'asc' => [Profile::tableName() . '.name' => SORT_ASC],
            'desc' => [Profile::tableName() . '.name' => SORT_DESC]
        ];
        $dataProvider->sort->attributes['surname'] = [
            'asc' => [Profile::tableName() . '.surname' => SORT_ASC],
            'desc' => [Profile::tableName() . '.surname' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['ynp'] = [
            'asc' => [Profile::tableName() . '.ynp' => SORT_ASC],
            'desc' => [Profile::tableName() . '.ynp' => SORT_DESC]
        ];
        $dataProvider->sort->attributes['firmName'] = [
            'asc' => [Profile::tableName() . '.firmName' => SORT_ASC],
            'desc' => [Profile::tableName() . '.firmName' => SORT_DESC]
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
                'id' => $this->id,
                'status_id' => $this->status_id,
                'role' => $this->role,
                'FROM_UNIXTIME(created_at, "%d.%m.%Y")' => $this->created_at,
                'FROM_UNIXTIME(updated_at, "%d.%m.%Y")' => $this->updated_at
            ]
        );

        $query->andFilterWhere(['like', Profile::tableName() . '.name', $this->name]);
        $query->andFilterWhere(['like', Profile::tableName() . '.surname', $this->surname]);

        $query->andFilterWhere(['like', Profile::tableName() . '.ynp', $this->ynp]);
        $query->andFilterWhere(['like', Profile::tableName() . '.firmName', $this->firmName]);

        $query->andFilterWhere(['like', 'username', $this->username]);
        $query->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
