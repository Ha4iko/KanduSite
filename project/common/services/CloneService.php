<?php

namespace common\services;

use common\models\Tournament;
use common\models\Bracket;
use common\models\Bracket\Group\Round as GroupRound;
use common\models\Bracket\Group\Group as GroupGroup;
use common\models\Bracket\Swiss\Round as SwissRound;
use common\models\Bracket\Relegation\Round as RelegationRound;
use Yii;

class CloneService
{
    /**
     * @param string $slug
     * @param int $bracketId
     * @return array|bool
     * @throws \Throwable
     */
    public function getCloneSlug($slug, $bracketId = 0)
    {
        if (null === $tournament = Tournament::findOne(['slug' => $slug])) return false;

        $result = ['slug' => '', 'bracketId' => 0];

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $prizes = $tournament->tournamentPrizes;
            $rules = $tournament->tournamentRules;
            $medias = $tournament->tournamentMedias;
            $schedules = $tournament->tournamentSchedules;
            $players = $tournament->tournamentToPlayer;
            $teams = $tournament->tournamentToTeam;
            $brackets = $tournament->brackets;


            $tournament->setIsNewRecord(true);
            $tournament->id = null;
            $tournament->slug = $tournament->slug . '-draft';
            $tournament->status = Tournament::STATUS_DRAFT;
            if (!$tournament->save()) {
                throw new \Exception('Tournament not saved');
            }

            foreach ($prizes as $prize) {
                $prize->setIsNewRecord(true);
                $prize->id = null;
                $prize->tournament_id = $tournament->id;
                if (!$prize->save()) {
                    throw new \Exception('Prize not saved');
                }
            }

            foreach ($rules as $rule) {
                $rule->setIsNewRecord(true);
                $rule->id = null;
                $rule->tournament_id = $tournament->id;
                if (!$rule->save()) {
                    throw new \Exception('Rule not saved');
                }
            }

            foreach ($medias as $media) {
                $media->setIsNewRecord(true);
                $media->id = null;
                $media->tournament_id = $tournament->id;
                if (!$media->save()) {
                    throw new \Exception('Media not saved');
                }
            }

            foreach ($schedules as $schedule) {
                $schedule->setIsNewRecord(true);
                $schedule->id = null;
                $schedule->tournament_id = $tournament->id;
                if (!$schedule->save()) {
                    throw new \Exception('Schedule not saved');
                }
            }

            $ttpNewIds = [];
            foreach ($players as $player) {
                $oldId = $player->id;
                $player->setIsNewRecord(true);
                $player->id = null;
                $player->tournament_id = $tournament->id;
                if (!$player->save()) {
                    throw new \Exception('Player participant not saved');
                }
                $ttpNewIds[$oldId] = $player->id;
            }

            $tttNewIds = [];
            foreach ($teams as $team) {
                $oldId = $team->id;
                $team->setIsNewRecord(true);
                $team->id = null;
                $team->tournament_id = $tournament->id;
                if (!$team->save()) {
                    throw new \Exception('Team participant not saved');
                }
                $tttNewIds[$oldId] = $team->id;
            }

            foreach ($brackets as $bracket) {
                $bracketOld = clone $bracket;
                $bracket->setIsNewRecord(true);
                $bracket->id = null;
                $bracket->tournament_id = $tournament->id;
                if (!$bracket->save()) {
                    throw new \Exception('Bracket not saved');
                }

                $this->cloneBracketEntities($bracket, $bracketOld, $bracket->bracket_type, $ttpNewIds, $tttNewIds);

                if ($bracketId && $bracketOld->id == $bracketId) {
                    $result['bracketId'] = $bracket->id;
                }
            }

            $transaction->commit();
            $result['slug'] = $tournament->slug;
            return $result;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }


    protected function cloneBracketEntities(Bracket $bracketTo, Bracket $bracketFrom, $typeId, $ttpNewIds, $tttNewIds)
    {
        switch ($typeId) {
            case Bracket::TYPE_GROUP:
                $this->cloneGroupEntities($bracketTo, $bracketFrom, $ttpNewIds, $tttNewIds);
                break;
            case Bracket::TYPE_SWISS:
                $this->cloneSwissEntities($bracketTo, $bracketFrom, $ttpNewIds, $tttNewIds);
                break;
            case Bracket::TYPE_RELEGATION:
                $this->cloneRelegationEntities($bracketTo, $bracketFrom, $ttpNewIds, $tttNewIds);
                break;
            case Bracket::TYPE_TABLE:
                $this->cloneTableEntities($bracketTo, $bracketFrom, $ttpNewIds, $tttNewIds);
                break;
            default:
                throw new \Exception('Unknown clone case');
        }
    }


    private function cloneGroupEntities(Bracket $bracketTo, Bracket $bracketFrom, $ttpNewIds, $tttNewIds)
    {
        $teamMode = (boolean) $bracketFrom->tournament->type->team_mode;

        $roundNewIds = [];
        foreach (GroupRound::findAll(['bracket_id' => $bracketFrom->id]) as $round) {
            $oldId = $round->id;
            $round->setIsNewRecord(true);
            $round->id = null;
            $round->bracket_id = $bracketTo->id;
            if (!$round->save()) {
                throw new \Exception('Round not saved');
            }
            $roundNewIds[$oldId] = $round->id;
        }


        $groupNewIds = [];
        foreach (GroupGroup::findAll(['bracket_id' => $bracketFrom->id]) as $group) {
            $oldId = $group->id;
            $group->setIsNewRecord(true);
            $group->id = null;
            $group->bracket_id = $bracketTo->id;
            if (!$group->save()) {
                throw new \Exception('Group not saved');
            }
            $groupNewIds[$oldId] = $group->id;
        }

        foreach (GroupRound::findAll(['bracket_id' => $bracketFrom->id]) as $round) {
            foreach ($round->getDuels(true) as $duel) {
                $oldRoundId = $duel->round_id;
                $oldGroupId = $duel->group_id;
                $duel->setIsNewRecord(true);
                $duel->id = null;
                $duel->round_id = $roundNewIds[$oldRoundId];
                $duel->group_id = $groupNewIds[$oldGroupId];
                if ($teamMode) {
                    if ($duel->team_one_id) $duel->team_one_id = $tttNewIds[$duel->player_one_id];
                    if ($duel->team_two_id) $duel->team_two_id = $tttNewIds[$duel->player_two_id];
                    if ($duel->winner_id) $duel->winner_id = $tttNewIds[$duel->winner_id];
                    if ($duel->loser_id) $duel->loser_id = $tttNewIds[$duel->loser_id];
                } else {
                    if ($duel->player_one_id) $duel->player_one_id = $ttpNewIds[$duel->player_one_id];
                    if ($duel->player_two_id) $duel->player_two_id = $ttpNewIds[$duel->player_two_id];
                    if ($duel->winner_id) $duel->winner_id = $ttpNewIds[$duel->winner_id];
                    if ($duel->loser_id) $duel->loser_id = $ttpNewIds[$duel->loser_id];
                }
                if (!$duel->save()) {
                    throw new \Exception('Duel not saved');
                }
            }
        }
    }


    private function cloneTableEntities(Bracket $bracketTo, Bracket $bracketFrom, $ttpNewIds, $tttNewIds)
    {
        $teamMode = (boolean) $bracketFrom->tournament->type->team_mode;

        foreach ($bracketFrom->bracketTableColumns as $column) {
            $column->setIsNewRecord(true);
            $column->id = null;
            $column->bracket_id = $bracketTo->id;
            if (!$column->save()) {
                throw new \Exception('Column not saved');
            }
        }
        foreach ($bracketFrom->bracketTableRows as $row) {
            $row->setIsNewRecord(true);
            $row->id = null;
            $row->bracket_id = $bracketTo->id;
            if ($row->tournament_to_player_id) $row->tournament_to_player_id = $ttpNewIds[$row->tournament_to_player_id];

            if (!$row->save()) {
                throw new \Exception('Row not saved');
            }
        }
        foreach ($bracketFrom->bracketTableRowsTeam as $row) {
            $row->setIsNewRecord(true);
            $row->id = null;
            $row->bracket_id = $bracketTo->id;
            if ($row->tournament_to_team_id) $row->tournament_to_team_id = $tttNewIds[$row->tournament_to_team_id];

            if (!$row->save()) {
                throw new \Exception('Row not saved');
            }
        }
    }


    private function cloneRelegationEntities(Bracket $bracketTo, Bracket $bracketFrom, $ttpNewIds, $tttNewIds)
    {
        $teamMode = (boolean) $bracketFrom->tournament->type->team_mode;

        $roundNewIds = [];
        foreach (RelegationRound::findAll(['bracket_id' => $bracketFrom->id]) as $round) {
            $oldId = $round->id;
            $round->setIsNewRecord(true);
            $round->id = null;
            $round->bracket_id = $bracketTo->id;
            if (!$round->save()) {
                throw new \Exception('Round not saved');
            }
            $roundNewIds[$oldId] = $round->id;
        }

        foreach (RelegationRound::findAll(['bracket_id' => $bracketFrom->id]) as $round) {
            foreach ($round->getDuels(true) as $duel) {
                $oldRoundId = $duel->round_id;
                $duel->setIsNewRecord(true);
                $duel->id = null;
                $duel->round_id = $roundNewIds[$oldRoundId];
                if ($teamMode) {
                    if ($duel->team_one_id) $duel->team_one_id = $tttNewIds[$duel->player_one_id];
                    if ($duel->team_two_id) $duel->team_two_id = $tttNewIds[$duel->player_two_id];
                    if ($duel->winner_id) $duel->winner_id = $tttNewIds[$duel->winner_id];
                    if ($duel->loser_id) $duel->loser_id = $tttNewIds[$duel->loser_id];
                } else {
                    if ($duel->player_one_id) $duel->player_one_id = $ttpNewIds[$duel->player_one_id];
                    if ($duel->player_two_id) $duel->player_two_id = $ttpNewIds[$duel->player_two_id];
                    if ($duel->winner_id) $duel->winner_id = $ttpNewIds[$duel->winner_id];
                    if ($duel->loser_id) $duel->loser_id = $ttpNewIds[$duel->loser_id];
                }
                if (!$duel->save()) {
                    throw new \Exception('Duel not saved');
                }
            }
        }
    }


    private function cloneSwissEntities(Bracket $bracketTo, Bracket $bracketFrom, $ttpNewIds, $tttNewIds)
    {
        $teamMode = (boolean) $bracketFrom->tournament->type->team_mode;

        $roundNewIds = [];
        foreach (SwissRound::findAll(['bracket_id' => $bracketFrom->id]) as $round) {
            $oldId = $round->id;
            $round->setIsNewRecord(true);
            $round->id = null;
            $round->bracket_id = $bracketTo->id;
            if (!$round->save()) {
                throw new \Exception('Round not saved');
            }
            $roundNewIds[$oldId] = $round->id;
        }

        foreach (SwissRound::findAll(['bracket_id' => $bracketFrom->id]) as $round) {
            foreach ($round->getDuels(true) as $duel) {
                $oldRoundId = $duel->round_id;
                $duel->setIsNewRecord(true);
                $duel->id = null;
                $duel->round_id = $roundNewIds[$oldRoundId];
                if ($teamMode) {
                    if ($duel->team_one_id) $duel->team_one_id = $tttNewIds[$duel->player_one_id];
                    if ($duel->team_two_id) $duel->team_two_id = $tttNewIds[$duel->player_two_id];
                    if ($duel->winner_id) $duel->winner_id = $tttNewIds[$duel->winner_id];
                    if ($duel->loser_id) $duel->loser_id = $tttNewIds[$duel->loser_id];
                } else {
                    if ($duel->player_one_id) $duel->player_one_id = $ttpNewIds[$duel->player_one_id];
                    if ($duel->player_two_id) $duel->player_two_id = $ttpNewIds[$duel->player_two_id];
                    if ($duel->winner_id) $duel->winner_id = $ttpNewIds[$duel->winner_id];
                    if ($duel->loser_id) $duel->loser_id = $ttpNewIds[$duel->loser_id];
                }
                if (!$duel->save()) {
                    throw new \Exception('Duel not saved');
                }
            }
        }
    }
}