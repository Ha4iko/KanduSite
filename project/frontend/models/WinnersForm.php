<?php

namespace frontend\models;

use common\models\TournamentPrize;
use common\services\RewardService;
use common\services\TournamentService;
use Yii;
use yii\base\Model;
use yii\web\HttpException;

/**
 * Winners form
 *
 * @property integer $tournament_id
 * @property string $prize_one
 * @property string $prize_two
 * @property string $prize_three
 * @property string $prize_four
 * @property array $prizes
 * @property array $prizesOld
 * @property string $player_one
 * @property string $player_two
 * @property string $player_three
 * @property string $player_four
 */
class WinnersForm extends Model
{
    /**
     * @var $rewardService RewardService
     */
    private $rewardService;

    /**
     * @var TournamentService
     */
    private $tournamentService;

    /**
     * @var Tournament $tournament
     */
    public $tournament;

    /**
     * @var integer|null
     */
    public $tournament_id;

    public $prize_one;
    public $prize_two;
    public $prize_three;
    public $prize_four;

    /**
     * @var array dynamic prizes
     */
    public $prizes = [];

    public $player_one;
    public $player_two;
    public $player_three;
    public $player_four;

    /**
     * @throws HttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        parent::init();
        $this->rewardService = Yii::$container->get(RewardService::class);
        $this->tournamentService = Yii::$container->get(TournamentService::class);
        $this->getTournament();
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['tournament_id'], 'integer'],
            [['prize_one', 'prize_two', 'prize_three', 'prize_four'], 'string', 'max' => 50],
            [['player_one', 'player_two', 'player_three', 'player_four'], 'integer'],
            [['prizes'], 'safe'],
        ];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPrizesSpecial()
    {
        return TournamentPrize::find()
            ->where([
                'tournament_id' => $this->tournament->id,
                'type_id' => TournamentPrize::TYPE_SPECIAL,
            ])
            ->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPrizesSecondary()
    {
        return TournamentPrize::find()
            ->where([
                'tournament_id' => $this->tournament->id,
                'type_id' => TournamentPrize::TYPE_SECONDARY,
            ])
            ->all();
    }

    /**
     * @param $attribute
     * @return mixed|string
     * @throws HttpException
     */
    public function getTournamentAttributeLabel($attribute)
    {
        $labels = $this->getTournament()->attributeLabels();
        return isset($labels[$attribute]) ? $labels[$attribute] : $this->generateAttributeLabel($attribute);
    }

    /**
     * Load form data.
     *
     * @return $this
     * @throws HttpException
     */
    public function loadFromTournamentModel()
    {
        $tournament = $this->getTournament();

        $this->prize_one = $tournament->prize_one;
        $this->prize_two = $tournament->prize_two;
        $this->prize_three = $tournament->prize_three;
        $this->prize_four = $tournament->prize_four;

        foreach ($tournament->tournamentPrizes as $prize) {
            if ($prize->type_id == TournamentPrize::TYPE_SPECIAL) {
                $winnerId = $tournament->getWinnerParticipantPlayer(null, $prize->id, null);
                $this->prizes[$prize->id] = $winnerId;
            } elseif ($prize->type_id == TournamentPrize::TYPE_SECONDARY) {
                $winnerId = $tournament->getWinnerParticipantPlayer(null, null, $prize->id);
                $this->prizes[$prize->id] = $winnerId;
            }
        }

        $this->player_one = $tournament->getWinnerParticipantPlayer(1);
        $this->player_two = $tournament->getWinnerParticipantPlayer(2);
        $this->player_three = $tournament->getWinnerParticipantPlayer(3);
        $this->player_four = $tournament->getWinnerParticipantPlayer(4);

        return $this;
    }

    /**
     * @param string|int|null $tournamentId
     * @param bool $throwIfNotFound
     * @return Tournament|null
     * @throws HttpException
     */
    public function getTournament($tournamentId = null, $throwIfNotFound = true)
    {
        if (!is_object($this->tournament)) {
            if ($this->tournament_id !== null) {
                $this->tournament = Tournament::findOne($this->tournament_id);
            } elseif ($tournamentId !== null) {
                $this->tournament = Tournament::findOne($tournamentId);
            }
        }

        if ($throwIfNotFound && !is_object($this->tournament)) {
            throw new HttpException(500, 'Get tournament fail');
        }

        return $this->tournament;
    }

    /**
     * @return bool
     * @throws HttpException
     * @throws \Throwable
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $this->saveStandardPrizes();
        $this->saveDynamicPrizes();
        return true;
    }

    /**
     * @throws HttpException
     * @throws \Throwable
     */
    private function saveStandardPrizes()
    {
        $tournament = $this->getTournament();

        if ($this->player_one) {
            $this->rewardService->removeRewardForPlayers($tournament->id, 1, null);
            $this->rewardService->addRewardForPlayer($tournament->id, $this->player_one, 1, null);
        } else {
            $this->rewardService->removeRewardForPlayers($tournament->id, 1, null);
        }

        if ($this->player_two) {
            $this->rewardService->removeRewardForPlayers($tournament->id, 2, null);
            $this->rewardService->addRewardForPlayer($tournament->id, $this->player_two, 2, null);
        } else {
            $this->rewardService->removeRewardForPlayers($tournament->id, 2, null);
        }

        if ($this->player_three) {
            $this->rewardService->removeRewardForPlayers($tournament->id, 3, null);
            $this->rewardService->addRewardForPlayer($tournament->id, $this->player_three, 3, null);
        } else {
            $this->rewardService->removeRewardForPlayers($tournament->id, 3, null);
        }

        if ($this->player_four) {
            $this->rewardService->removeRewardForPlayers($tournament->id, 4, null);
            $this->rewardService->addRewardForPlayer($tournament->id, $this->player_four, 4, null);
        } else {
            $this->rewardService->removeRewardForPlayers($tournament->id, 4, null);
        }

    }

    /**
     * @throws HttpException
     * @throws \Throwable
     */
    private function saveDynamicPrizes()
    {
        $tournament = $this->getTournament();

        foreach ($this->prizes as $prizeId => $playerId) {
            if (!$prizeId) continue;
            if (!$prize = TournamentPrize::findOne(['id' => $prizeId])) continue;
            if ($prize->type_id == TournamentPrize::TYPE_SPECIAL) {
                $this->rewardService->removeRewardForPlayers($tournament->id, null, $prizeId, null);
                if ($playerId) {
                    $this->rewardService->addRewardForPlayer($tournament->id,
                        $playerId, null, $prizeId, null);
                }
            } elseif ($prize->type_id == TournamentPrize::TYPE_SECONDARY) {
                $this->rewardService->removeRewardForPlayers($tournament->id, null, null, $prizeId);
                if ($playerId) {
                    $this->rewardService->addRewardForPlayer($tournament->id,
                        $playerId, null, null, $prizeId);
                }
            }
        }
    }

    // /**
    //  * @param bool $onlyNicks
    //  * @return array|Player[]
    //  * @throws HttpException
    //  */
    // public function getParticipantsWithoutRewards($onlyNicks = false)
    // {
    //     return $this->getTournament()->getParticipantsWithoutRewards($onlyNicks);
    // }

    // /**
    //  * @param bool $onlyNicks
    //  * @return array|Player[]
    //  * @throws HttpException
    //  */
    // public function getParticipantsPlayers($onlyNicks = false)
    // {
    //     return $this->getTournament()->getParticipantsPlayers($onlyNicks);
    // }


    /**
     * @return array
     * @throws \Throwable
     */
    public function getTournamentParticipants()
    {
        return $this->tournamentService->getParticipantsForBracket($this->tournament_id, true);
    }
}
