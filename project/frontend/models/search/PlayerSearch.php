<?php

namespace frontend\models\search;

use frontend\models\Player;
use frontend\models\Tournament;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\User;
use yii\helpers\ArrayHelper;

/**
 * PlayerSearch represents the model behind the search form of Player.
 */
class PlayerSearch extends Player
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['nick'], 'string', 'max' => 255],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param null|string $formName
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = static::find()->orderBy(['nick']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->setPagination([
            'pageSize' => 10,
            'defaultPageSize' => 10,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'nick', trim($this->nick)]);

        return $dataProvider;
    }
}
