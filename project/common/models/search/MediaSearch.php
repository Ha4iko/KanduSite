<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Media;

/**
 * MediaSearch represents the model behind the search form of `common\models\Media`.
 */
class MediaSearch extends Media
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['is_text', 'is_video', 'active'], 'integer'],
            [['title', 'bg_image', 'slug'], 'string', 'max' => 255],
            [['content'], 'string'],
        ];
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
        $query = static::find();

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
            'is_text' => $this->is_text,
            'is_video' => $this->is_video,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
