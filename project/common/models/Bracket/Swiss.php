<?php

namespace common\models\Bracket;

use common\services\Bracket\SwissService;
use Yii;
use common\models\Bracket;
use common\models\Bracket\Swiss\Duel;
use common\models\Bracket\Swiss\PlayerDuel;
use common\models\Bracket\Swiss\Round;
use common\models\Bracket\Swiss\TeamDuel;

/**
 * Class Relegation
 *
 * @property Round[] $rounds
 */
class Swiss extends Bracket
{
    /**
     * @var SwissService
     */
    private $swissService;

    /**
     * @throws \Throwable
     */
    public function init()
    {
        parent::init();

        $this->swissService = Yii::$container->get(SwissService::class);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRounds()
    {
        return $this->hasMany(Round::class, ['bracket_id' => 'id'])
            ->orderBy(['order' => SORT_ASC]);
    }

    /**
     * @param null $round
     * @return Duel[]
     */
    public function getDuels($round = null) {
        $roundIds = Round::find()
            ->where(['bracket_id' => $this->id])
            ->andFilterWhere(['order' => $round])
            ->orderBy(['order' => SORT_ASC])
            ->select('id')->column();

        $teamsMode = $this->tournament->type->team_mode;

        if ($teamsMode) {
            $duels = TeamDuel::find()
                ->where(['round_id' => $roundIds])
                ->orderBy('order')
                ->all();

        } else {
            $duels = PlayerDuel::find()
                ->where(['round_id' => $roundIds])
                ->orderBy('order')
                ->all();
        }
        return Duel::fromCollection($duels);
    }

    /**
     * @return int
     */
    public function getCompletedDuelsCount() {
        $duels = $this->getDuels();
        $count = 0;
        foreach ($duels as $duel) {
            if ((intval($duel->score_one) + intval($duel->score_two)) >= $this->best_of) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @return array
     */
    public function getStandings()
    {
        return $this->swissService->getStandings($this->id);
    }


    /**
     * @return bool
     */
    public function getLastRoundOrderWithParticipants() {
        $ordersOfRounds = [];
        foreach ($this->rounds as $round) {
            foreach ($round->getDuels() as $duel) {
                if ($duel->player_1 || $duel->player_2) {
                    $ordersOfRounds[] = $round->order;
                }
            }
        }
        return max($ordersOfRounds);
    }

    /**
     * @return bool
     */
    public function isFilledFirstRound() {
        $round = Round::findOne(['order' => 1, 'bracket_id' => $this->id]);

        if (!$round) return false;

        $duels = $round->getDuels();
        $players = 0;
        foreach ($duels as $duel) {
            if ($duel->player_1) {
                $players++;
            }
            if ($duel->player_2) {
                $players++;
            }
        }

        return $players === $round->bracket->participants;
    }

}