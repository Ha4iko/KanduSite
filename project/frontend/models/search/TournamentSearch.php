<?php

namespace frontend\models\search;

use common\helpers\DataTransformHelper;
use common\models\TournamentType;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Tournament;
use yii\helpers\ArrayHelper;

/**
 * TournamentSearch represents the model behind the search form of `common\models\Tournament`.
 */
class TournamentSearch extends Tournament
{
    public $inCabinet = false;

    /**
     * @var array
     */
    public static $orderList = [
        'date' => [
            'id' => '',
            'label' => 'date',
            'sort' => ['date' => SORT_DESC, 'id' => SORT_DESC],
        ],
        'pool' => [
            'id' => 'pool',
            'label' => 'prize pool',
            'sort' => ['pool' => SORT_DESC],
        ],
        'type' => [
            'id' => 'type',
            'label' => 'type',
            'sort' => ['type_id' => SORT_ASC],
        ],
        'status' => [
            'id' => 'status',
            'label' => 'status',
            'sort' => ['status' => SORT_ASC],
        ],
        'title' => [
            'id' => 'title',
            'label' => 'title',
            'sort' => ['title' => SORT_ASC],
        ],
    ];

    /**
     * Get order labels
     *
     * @return array
     */
    public static function getOrderLabels()
    {
        return ArrayHelper::map(static::$orderList, 'id', 'label');
    }

    /**
     * @var array
     */
    public $order = 'date';


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'safe'],
            ['type', 'safe'],
            [['title', 'date_begin', 'order'], 'safe'],
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
     * @param null|string $formName
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $typeIdsBySlug = DataTransformHelper::getList(TournamentType::class, 'id', 'slug');

        $query = Tournament::find()->where(['<>', 'status', Tournament::STATUS_DRAFT]);

        if (!$this->inCabinet) {
            $query->andWhere(['<>', 'status', Tournament::STATUS_PENDING]);
        }

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

        // grid filtering conditions
        // if ($this->type_id == 'bsg') {
        //     $query->innerJoin('tournament_type tt', 'tt.id = tournament.type_id')
        //         ->andWhere(['tt.bsg' => 1]);
        // } else {
            $query->andFilterWhere([
                'type_id' => isset($typeIdsBySlug[trim($this->type_id)]) ? $typeIdsBySlug[trim($this->type_id)] : null,
            ]);
        // }
        $query->andFilterWhere([
            'date' => trim($this->date),
            'status' => Tournament::getStatusIdBySlug($this->status),
            'organizer_id' => $this->organizer_id
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        $query->orderBy(static::$orderList[$this->order]['sort']);

        return $dataProvider;
    }


    /**
     * @return integer
     */
    public function getType() // for pretty param in url
    {
        return $this->type_id;
    }

    /**
     * @param $value integer
     */
    public function setType($value) // for pretty param in url
    {
        $this->type_id = $value ?: null;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getDate_begin() // for pretty param in url
    {
        return is_null($this->date) ? '' : Yii::$app->formatter->asDate($this->date, 'php:m/d/Y');
    }

    /**
     * @param string $date
     * @throws \yii\base\InvalidConfigException
     */
    public function setDate_begin($date) // for pretty param in url
    {
        $this->date = $date ? Yii::$app->formatter->asDate($date, 'php:Y-m-d') : null;
    }

}
