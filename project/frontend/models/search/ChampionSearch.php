<?php

namespace frontend\models\search;

use common\helpers\DataTransformHelper;
use common\models\TournamentType;
use common\services\TournamentService;
use frontend\models\Tournament;
use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * ChampionSearch
 * @property string $order
 * @property string $name
 * @property string $date
 * @property string $type
 */
class ChampionSearch extends Model
{
    /**
     * @var $tournamentService TournamentService
     */
    private $tournamentService;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        $this->tournamentService = Yii::$container->get(TournamentService::class);
        parent::init();
    }

    /**
     * @var array
     */
    public static $orderList = [
        'date' => [
            'id' => '',
            'label' => 'date',
            'sort' => 'tour.date desc',
        ],
        'pool' => [
            'id' => 'pool',
            'label' => 'prize pool',
            'sort' => 'tour.pool desc',
        ],
        'type' => [
            'id' => 'type',
            'label' => 'type',
            'sort' => 'tour.type_id asc',
        ],
        'status' => [
            'id' => 'status',
            'label' => 'status',
            'sort' => 'tour.status asc',
        ],
        'title' => [
            'id' => 'title',
            'label' => 'title',
            'sort' => 'tour.title asc',
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

    public $name = '';
    public $date = '';
    public $type = '';

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['type', 'safe'],
            [['name', 'date', 'order'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @param null $formName
     * @return SqlDataProvider
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function search($params, $formName = null)
    {
        $typeIdsBySlug = DataTransformHelper::getList(TournamentType::class, 'id', 'slug');

        $this->load($params, $formName);

        // filtering
        $joins = [];
        $conditions = [];

        $conditions[] = 'tour.status = ' . Tournament::STATUS_COMPLETED;

        // if (trim($this->type) == 'bsg') {
        //     $joins[] = ' left join tournament_type tt on tt.id = tour.type_id ';
        //     $conditions[] = 'tt.bsg = 1';
        // } elseif (trim($this->type)) {
        if (trim($this->type)) {
            $typeIdBySlug = isset($typeIdsBySlug[trim($this->type)]) ? $typeIdsBySlug[trim($this->type)] : null;
            $typeIdBySlug = intval($typeIdBySlug);
            if ($typeIdBySlug) {
                $conditions[] = 'tour.type_id = ' . $typeIdBySlug;
            }
        }
        if (trim($this->date)) {
            $conditions[] = 'tour.date = "' . Html::encode(Yii::$app->formatter->asDate(trim($this->date), 'php:Y-m-d')) . '"';
        }
        if (trim($this->name)) {
            $joins[] = ' left join player pl on pl.id = winners.player_id ';
            $joins[] = ' left join team tm on tm.id = winners.team_id ';
            $conditions[] = ' (pl.nick like "%' . trim(Html::encode($this->name)) . '%"' .
                ' or tm.name like "%' . trim(Html::encode($this->name)) . '%") ';
        }

        // order
        if (!$this->order) $this->order = 'date';

        $query = $this->tournamentService->getChampionsRelations(
            0, 0, $conditions, static::$orderList[$this->order]['sort'], $joins, 'sql');

        $count = Yii::$app->db->createCommand("select count(*) from ({$query}) subquery")
            ->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => $query,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
                'defaultPageSize' => 10,
            ],
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}
