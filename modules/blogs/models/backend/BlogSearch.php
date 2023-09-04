<?php

namespace common\modules\blogs\models\backend;

use yii\data\ActiveDataProvider;

/**
 * Blog search model.
 */
class BlogSearch extends Blog
{

    public function attributes()
    {
        return array_merge(parent::attributes(), ['tbl_blogs_category.title']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Integer
            [['id', 'category_id'], 'integer'],
            // String
            [['snippet', 'content'], 'string'],
            [['title', 'alias'], 'string', 'max' => 255],
            // Status
            ['status_id', 'in', 'range' => array_keys(self::getStatusArray())],
            // Date
            [['created_at', 'updated_at'], 'date', 'format' => 'd.m.Y'],
            //[['tbl_blogs_category.title'], 'safe']
        ];
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
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /*
        $dataProvider->sort->attributes['{{%blogs_category}}.title'] = [
            'asc' => ['tbl_blogs_category.title' => SORT_ASC],
            'desc' => ['tbl_blogs_category.title' => SORT_DESC],
        ];

        $query->joinWith(['categories']);
        */

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
                'id' => $this->id,
                'status_id' => $this->status_id,
                'category_id' => $this->category_id,
                'FROM_UNIXTIME(created_at, "%d.%m.%Y")' => $this->created_at,
                'FROM_UNIXTIME(updated_at, "%d.%m.%Y")' => $this->updated_at,
                //'category_id' => $this->getAttribute('categories.title')
            ]
        );

        $query->andFilterWhere(['like', 'alias', $this->alias]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'snippet', $this->snippet]);
        $query->andFilterWhere(['like', 'content', $this->content]);
        //$query->andFilterWhere(['like', 'categories.title', $this->content]);

        return $dataProvider;
    }
}
