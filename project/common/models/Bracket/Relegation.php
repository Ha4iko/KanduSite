<?php

namespace common\models\Bracket;

use common\models\Bracket;
use common\models\Bracket\Relegation\Duel;
use common\models\Bracket\Relegation\PlayerDuel;
use common\models\Bracket\Relegation\Round;
use common\models\Bracket\Relegation\TeamDuel;

/**
 * Class Relegation
 *
 * @property Round[] $rounds
 * @property Round[] $roundsMain
 * @property Round[] $roundsDefeat
 * @property Round[] $roundsGrand
 * @property int $insertedParticipantsCount
 * @property int $completedDuelsCount
 */
class Relegation extends Bracket {

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRounds()
    {
        return $this->hasMany(Round::class, ['bracket_id' => 'id'])
            ->orderBy(['level' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoundsMain()
    {
        return $this->getRounds()->where(['type_id' => Round::TYPE_MAIN]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoundsDefeat()
    {
        return $this->getRounds()->where(['type_id' => Round::TYPE_DEFEAT]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoundsGrand()
    {
        return $this->getRounds()->where(['type_id' => Round::TYPE_GRAND]);
    }

    /**
     * @return int
     */
    public function getInsertedParticipantsCount() {
        $duels = $this->getDuels();
        $count = 0;
        foreach ($duels as $duel) {
            if ($duel->player_1) {
                $count++;
            }
            if ($duel->player_2) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @param bool $byKeys
     * @return array
     */
    public function getInsertedParticipantIds($byKeys = false) {
        $duels = $this->getDuels();
        $ids = [];
        foreach ($duels as $duel) {
            if ($duel->player_1) {
                $ids[$duel->player_1] = $duel->player_1;
            }
            if ($duel->player_2) {
                $ids[$duel->player_2] = $duel->player_2;
            }
        }
        return $byKeys ? $ids : array_keys($ids);
    }

    /**
     * @return array
     */
    public function getInsertedCompletedParticipantIds($byKeys = false) {
        $duels = $this->getDuels();
        $ids = [];
        foreach ($duels as $duel) {

            if ($duel->player_1 && $duel->player_2 &&
                ((intval($duel->score_one) + intval($duel->score_two)) >= $this->best_of)
            ) {
                $ids[$duel->player_1] = $duel->player_1;
                $ids[$duel->player_2] = $duel->player_2;
            }
        }
        return $byKeys ? $ids : array_keys($ids);
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
     * @param $roundType
     * @param null $level
     * @return Duel[]
     */
    public function getDuels($roundType = null, $level = null) {
        $roundIds = Round::find()
            ->where(['bracket_id' => $this->id])
            ->andFilterWhere(['type_id' => $roundType])
            ->andFilterWhere(['level' => $level])
            ->orderBy(['level' => SORT_ASC])
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
     * @param int $id
     * @return Duel
     */
    public function getDuelById($id) {
        $teamsMode = $this->tournament->type->team_mode;

        if ($teamsMode) {
            $duel = TeamDuel::find()
                ->where(['id' => $id])
                ->one();
        } else {
            $duel = PlayerDuel::find()
                ->where(['id' => $id])
                ->one();
        }
        return Duel::from($duel);
    }

}