<?php

namespace common\models\Bracket;

use Yii;
use common\models\Bracket;
use common\models\Bracket\Group\Duel;
use common\models\Bracket\Group\PlayerDuel;
use common\models\Bracket\Group\Round;
use common\models\Bracket\Group\Group as GroupModel;
use common\models\Bracket\Group\TeamDuel;
use common\services\Bracket\GroupService;

/**
 * Class Group
 *
 * @property Round[] $rounds
 * @property Group[] $groups
 * @property int $insertedParticipantsCount
 * @property int $completedDuelsCount
 */
class Group extends Bracket
{

    /**
     * @var GroupService
     */
    private $groupService;

    /**
     * @throws \Throwable
     */
    public function init()
    {
        parent::init();

        $this->groupService = Yii::$container->get(GroupService::class);
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
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(GroupModel::class, ['bracket_id' => 'id'])
            ->orderBy('order');
    }

    /**
     * @param array $roundConditions
     * @return Duel[]
     */
    public function getDuels($roundConditions = []) {
        $roundIdsQuery = Round::find()
            ->where(['bracket_id' => $this->id])
            ->orderBy(['order' => SORT_ASC])
            ->select('id');

        if ($roundConditions) {
            $roundIdsQuery->andWhere($roundConditions);
        }

        $roundIds = $roundIdsQuery->column();

        $teamsMode = $this->tournament->type->team_mode;

        if ($teamsMode) {
            $duels = TeamDuel::find()
                ->where(['round_id' => $roundIds])
                ->orderBy('id')
                ->all();

        } else {
            $duels = PlayerDuel::find()
                ->where(['round_id' => $roundIds])
                ->orderBy('id')
                ->all();
        }
        return Duel::fromCollection($duels);
    }

    /**
     * @param array $groupConditions
     * @return Duel[]
     */
    public function getDuelsByGroups($groupConditions = []) {
        $groupIdsQuery = GroupModel::find()
            ->where(['bracket_id' => $this->id])
            ->orderBy(['order' => SORT_ASC])
            ->select('id');

        if ($groupConditions) {
            $groupIdsQuery->andWhere($groupConditions);
        }

        $groupIds = $groupIdsQuery->column();

        $teamsMode = $this->tournament->type->team_mode;

        if ($teamsMode) {
            $duels = TeamDuel::find()
                ->where(['group_id' => $groupIds])
                ->orderBy('id')
                ->all();

        } else {
            $duels = PlayerDuel::find()
                ->where(['group_id' => $groupIds])
                ->orderBy('id')
                ->all();
        }
        return Duel::fromCollection($duels);
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
        return $this->groupService->getStandings($this->id);
    }


    public function isManualParticipantsCompleted()
    {
        $completed = true;
        foreach ($this->rounds as $round) {
            foreach ($round->getDuels() as $duel) {
                if ($round->order == 1 && (!$duel->player_1 || !$duel->player_2) ) {
                    $completed = false;
                }
                if ($this->participants % 2 && $round->order == 2 && $duel->order == 1 && !$duel->player_1) {
                    $completed = false;
                }
            }
        }

        return $completed;
    }
}