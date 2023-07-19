<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Player;

/**
 * PlayerSearch represents the model behind the search form of `common\models\Player`.
 */
class PlayerSearch extends Player
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'class_id', 'race_id', 'faction_id', 'world_id', 'played', 'wins', 'defeats', 'winrate', 'bonus_points', 'all_points'], 'integer'],
            [['nick'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Player::find();

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
            'class_id' => $this->class_id,
            'race_id' => $this->race_id,
            'faction_id' => $this->faction_id,
            'world_id' => $this->world_id,
            'played' => $this->played,
            'wins' => $this->wins,
            'defeats' => $this->defeats,
            'winrate' => $this->winrate,
            'bonus_points' => $this->bonus_points,
            'all_points' => $this->all_points,
        ]);

        $query->andFilterWhere(['like', 'nick', $this->nick]);

        return $dataProvider;
    }
}
