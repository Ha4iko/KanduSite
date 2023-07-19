<?php

namespace frontend\models\search;

use common\helpers\DataTransformHelper;
use common\models\TournamentType;
use frontend\models\Bracket;
use frontend\models\BracketTableRow;
use common\services\BracketTableService;
use frontend\models\Player;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\Html;

/**
 * @property string $bracketId
 * @property string $nick
 */
class BracketTableRowSearch extends Model
{
    /**
     * @var string
     */
    public $nick;

    /**
     * @var int
     */
    public $bracketId;

    /**
     * @var BracketTableService
     */
    private $bracketService;

    /**
     * @var Bracket
     */
    private $bracket;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        parent::init();
        $this->bracketService = Yii::$container->get(BracketTableService::class);
        $this->bracketId = intval($this->bracketId);
        if (!$this->bracketId) throw new InvalidConfigException('Wrong bracket id.');

        $this->bracket = Bracket::findOne($this->bracketId);
        if (!is_object($this->bracket)) throw new InvalidConfigException('Bracket not found.');
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['nick'], 'string'],
        ];
    }

    /**
     * @param array $params
     * @param null $formName
     * @return ArrayDataProvider
     * @throws \Exception
     */
    public function search($params, $formName = null)
    {
        $tournamentId = $this->bracket->tournament_id;

        $this->load($params, $formName);

        // filtering
        $conditions = [];

        if (trim($this->nick)) {

            $conditions[] = 'p.nick like "%' . trim(Html::encode($this->nick)) . '%"';
        }

        $tableRows = $this->bracketService->getBracketTableRows($this->bracketId, $conditions);

        $rowNumber = 1;
        foreach ($tableRows as &$tableRow) {
            $tableRow['row_number'] = $rowNumber++;

            $playerAvatar = '';
            $playerLink = '';
            $factionAvatar = '';
            if ($player = Player::findOne($tableRow['player_id'])) {
                $playerAvatar = $player->getAvatar($tournamentId);
                $playerLink = $player->external_link ?: '';
                $classCollor = $player->getClassColor($tournamentId);
                $factionAvatar = $player->getFactionAvatar($tournamentId);
            }

            $tableRow['player_avatar'] = $playerAvatar;
            $tableRow['player_link'] = $playerLink;
            $tableRow['collor_class'] = $classCollor;
            $tableRow['faction_avatar'] = $factionAvatar;
        }

        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $tableRows,
            'pagination' => [
                'pageSize' => 100,
                'defaultPageSize' => 100,
            ],
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

}
