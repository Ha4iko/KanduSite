<?php

namespace common\services\Bracket;

use common\models\Bracket;
use common\models\Bracket\Group as GroupBracket;
use common\models\Bracket\Group\Group;
use common\models\Bracket\Group\PlayerDuel;
use common\models\Bracket\Group\Round;
use common\models\Bracket\Group\TeamDuel;
use common\models\Player;
use common\models\Team;
use common\models\TournamentToPlayer;
use common\models\TournamentToTeam;
use common\services\TournamentService;
use yii\helpers\ArrayHelper;

class GroupService
{

    /**
     * @var TournamentService
     */
    private $tournamentService;

    /**
     * RelegationService constructor.
     * @param TournamentService $tournamentService
     */
    public function __construct(
        TournamentService $tournamentService
    )
    {
        $this->tournamentService = $tournamentService;
    }

    /**
     * @param int $bracketId
     * @param int $participantsCount
     * @param int $groupCount
     * @throws \Throwable
     */
    public function createDuels(int $bracketId, int $participantsCount, int $groupCount)
    {
        $alphabet = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];

        $duelsMap = $this->generateDuelsMap($participantsCount);

        $groups = [];
        for ($i = 0; $i < $groupCount; $i++) {
            $group = new Group();
            $group->title = 'Group ' . strtoupper($alphabet[$i]);
            $group->bracket_id = $bracketId;
            $group->order = $i + 1;
            $group->active = 1;
            if (!$group->save()) throw new \Exception('Group not saved');

            $groups[$i + 1] = $group->id;
        }

        $rounds = [];
        foreach ($duelsMap as $roundOrder => $duels) {
            $round = new Round();
            $round->title = 'Round ' . $roundOrder;
            $round->bracket_id = $bracketId;
            $round->order = $roundOrder;
            $round->active = 1;
            if (!$round->save()) throw new \Exception('Round not saved');

            $rounds[$roundOrder] = $round->id;
        }

        foreach ($groups as $groupOrder => $groupId) {
            foreach ($duelsMap as $roundOrder => $duels) {
                $number = 0;
                foreach ($duels as $duelOrder => $duelIds) {
                    if (!$duelIds[0] || !$duelIds[1]) continue;
                    $number++;

                    if ($participantsCount % 2 && $roundOrder == 2 && $number == 1) {
                        $this->createDuel($bracketId, $rounds[$roundOrder], $groups[$groupOrder], $number, [
                            'scheme_one' => $duelIds[1],
                            'scheme_two' => $duelIds[0],
                            'active' => 1,
                        ]);
                    } else {
                        $this->createDuel($bracketId, $rounds[$roundOrder], $groups[$groupOrder], $number, [
                            'scheme_one' => $duelIds[0],
                            'scheme_two' => $duelIds[1],
                            'active' => 1,
                        ]);
                    }
                }
            }
        }

    }

    /**
     * @param int $bracketId
     * @throws \Throwable
     */
    public function clearAll(int $bracketId)
    {
        $bracket = GroupBracket::findOne($bracketId);
        foreach ($bracket->rounds as $round) {
            $round->delete();
        }
        foreach ($bracket->groups as $group) {
            $group->delete();
        }

    }


    /**
     * @param int $bracketId
     * @param array $participantIds
     * @param boolean $distributeByClass
     * @throws \Throwable
     */
    public function attachParticipantsToBracket(int $bracketId, array $participantIds, bool $distributeByClass)
    {
        $bracket = GroupBracket::findOne($bracketId);


        if ($distributeByClass) {
            $partIdsByGroups = $this->distributePlayersByClasses($bracket, $participantIds);
        } else {
            shuffle($participantIds);

            $partIdsByGroups = [];
            for ($i = 0; $i < $bracket->group_count; $i++) {
                for ($j = 0; $j < $bracket->participants; $j++) {
                    $participantIdToBracket = array_shift($participantIds);
                    if (!$participantIdToBracket) break 2;
                    $partIdsByGroups[$i + 1][$j + 1] = $participantIdToBracket;
                }
            }
        }

        foreach ($bracket->getDuels() as $duel) {
            $duel->player_1 = null;
            $duel->player_2 = null;
            $duel->winner_id = null;
            $duel->loser_id = null;
            $duel->score_one = null;
            $duel->score_two = null;
            $duel->active = 1;
            if (!$duel->save()) {
                throw new \Exception('Duel not saved');
            }
        }

        foreach ($partIdsByGroups as $groupOrder => $participantIds) {
            foreach ($participantIds as $schemeId => $participantId) {
                foreach ($bracket->getDuelsByGroups(['order' => $groupOrder]) as $duel) {
                    if ($duel->scheme_one && $schemeId == $duel->scheme_one) {
                        $duel->player_1 = $participantId;
                    } elseif ($duel->scheme_two && $schemeId == $duel->scheme_two) {
                        $duel->player_2 = $participantId;
                    }

                    if (!$duel->save()) {
                        throw new \Exception('Duel not saved');
                    }
                }
            }
        }

        $bracket->editable_scores = 1;
        if (!$bracket->save()) {
            throw new \Exception('Bracket not saved');
        }
    }

    /**
     * @param GroupBracket $bracket
     * @param array $participantIds
     */
    private function distributePlayersByClasses($bracket, $participantIds)
    {
        shuffle($participantIds);

        $resultByGroups = [];
        for ($i = 0; $i < $bracket->group_count; $i++) {
            for ($j = 0; $j < $bracket->participants; $j++) {
                $resultByGroups[$i + 1][$j + 1] = null;
            }
        }

        $groupedByClass = [];
        foreach ($participantIds as $participantId) {
            $participant = TournamentToPlayer::findOne(['id' => $participantId, 'tournament_id' => $bracket->tournament_id]);
            if (!is_object($participant)) throw new \Exception('Unknown participant');

            $groupedByClass[$participant->class_id]['count'] = isset($groupedByClass[$participant->class_id]['count'])
                ? $groupedByClass[$participant->class_id]['count'] + 1 : 1;
            $groupedByClass[$participant->class_id]['participants'][] = $participantId;
        }

        ArrayHelper::multisort($groupedByClass, ['count'], [SORT_DESC]);

        $idsToFill = [];
        foreach ($groupedByClass as $classId => $classData) {
            $idsToFill = array_merge($idsToFill, array_slice($classData['participants'], 0, $bracket->group_count));
        }

        for ($j = 0; $j < $bracket->participants; $j++) {
            for ($i = 0; $i < $bracket->group_count; $i++) {
                $participantIdToBracket = array_shift($idsToFill);
                if (!$participantIdToBracket) break 2;
                $resultByGroups[$i + 1][$j + 1] = $participantIdToBracket;
            }
        }

        return $resultByGroups;
    }

    /**
     * @param int $bracketId
     * @return bool is added participants to empty duels
     * @throws \Throwable
     */
    public function fillAutoParticipantsInBracket(int $bracketId)
    {
        $bracket = GroupBracket::findOne($bracketId);
        //if ($bracket->editable_scores) return false;

        $duels = $bracket->getDuels();
        if (!$duels) return false;

        $schemeLinks = [];
        foreach ($duels as $duel) {
            if ($duel->isBaseParticipantOne() && $duel->player_1 && $duel->scheme_one)
                $schemeLinks[$duel->group_id][$duel->player_1] = $duel->scheme_one;
            if ($duel->isBaseParticipantTwo() && $duel->player_2 && $duel->scheme_two)
                $schemeLinks[$duel->group_id][$duel->player_2] = $duel->scheme_two;
        }

        $baseCount = 0;
        foreach ($schemeLinks as $groupId => $links) {
            $baseCount += count($links);
        }

        if ($baseCount != ($bracket->participants * $bracket->group_count)) {
            return false;
        }

        foreach ($duels as $duel) {
            $scheme = array_flip($schemeLinks[$duel->group_id]);

            // $duel->winner_id = null;
            // $duel->loser_id = null;
            // $duel->score_one = null;
            // $duel->score_two = null;
            $duel->active = 1;

            if (!$duel->isBaseParticipantOne() && isset($scheme[$duel->scheme_one])) {
                $duel->player_1 = $scheme[$duel->scheme_one];
            }
            if (!$duel->isBaseParticipantTwo() && isset($scheme[$duel->scheme_two])) {
                $duel->player_2 = $scheme[$duel->scheme_two];
            }
            if (!$duel->save()) {
                throw new \Exception('Duel not saved');
            }
        }

        $bracket->editable_scores = 1;
        if (!$bracket->save()) {
            throw new \Exception('Bracket not saved');
        }

        return true;
    }


    /**
     * @param int $bracketId
     * @return bool is added participants to empty duels
     * @throws \Throwable
     */
    public function clearAutoParticipantsInBracket(int $bracketId)
    {
        $bracket = GroupBracket::findOne($bracketId);
        $duels = $bracket->getDuels();
        if (!$duels) return false;

        foreach ($duels as $duel) {
            if (!$duel->isBaseParticipantOne())
                $duel->player_1 = null;

            if (!$duel->isBaseParticipantTwo())
                $duel->player_2 = null;

            if (!$duel->save()) {
                throw new \Exception('Duel not saved');
            }
        }

        return true;
    }


    /**
     * @param int $bracketId
     * @return PlayerDuel|TeamDuel
     */
    public function duelFactory(int $bracketId) {
        $teamMode = Bracket::findOne($bracketId)->tournament->type->team_mode;
        if ($teamMode) {
            return new TeamDuel();
        } else {
            return new PlayerDuel();
        }
    }

    /**
     * @param int $bracketId
     * @param int $roundId
     * @param int $groupId
     * @param int $order
     * @param array $attributes
     * @throws \Throwable
     */
    public function createDuel(int $bracketId, int $roundId, int $groupId, int $order, array $attributes = []) {
        $duel = $this->duelFactory($bracketId);
        $duel->round_id = $roundId;
        $duel->group_id = $groupId;
        $duel->order = $order;
        $duel->setAttributes($attributes);
        if (!$duel->save()) {
            throw new \Exception('Duel not saved');
        }
    }

    /**
     * @param int $participantCount
     * @return array
     */
    private function generateDuelsMap(int $participantCount)
    {
        $participantCount = (int) $participantCount;
        $useZeroDuel = $participantCount % 2;
        $roundCount = $useZeroDuel ? $participantCount : $participantCount - 1;
        $participantCountEven = $participantCount + ($useZeroDuel ? 1 : 0);

        $idsRaw = array_keys(
            array_fill($useZeroDuel ? 0 : 1, $participantCount + ($useZeroDuel ? 1 : 0), null)
        );

        $fixedId = array_shift($idsRaw);
        $tapeIds = array_merge($idsRaw, $idsRaw);

        $duelsMap = [];
        for ($i = 1; $i < ($roundCount + 1); $i++) {
            $duelsMap[$i] = $this->generateDuels(array_merge([$fixedId], $tapeIds), $participantCountEven);
            array_shift($tapeIds);
        }

        return $duelsMap;
    }

    /**
     * @param $fullTapeIds
     * @param $participantCountEven
     * @return array
     */
    private function generateDuels($fullTapeIds, $participantCountEven)
    {
        $duelCount = $participantCountEven / 2;
        $firstIndex = 0;
        $secondIndex = $participantCountEven - 1;

        $duels = [];
        $offset = 0;
        for ($j = 1; $j < ($duelCount + 1); $j++) {
            $firstId =  $fullTapeIds[$firstIndex + $offset];
            $secondId =  $fullTapeIds[$secondIndex - $offset];
            $duels[$j] = [$firstId, $secondId];
            $offset++;
        }

        return $duels;
    }


    /**
     * @param int $bracketId GroupBracket id
     * @return array
     */
    public function getBracketParticipantsList(int $bracketId)
    {
        $bracket = Bracket\Group::findOne($bracketId);
        return $this->tournamentService->getParticipantsList($bracket->tournament_id);
    }

    /**
     * @param int $bracketId GroupBracket id
     * @return array
     */
    public function getStandings(int $bracketId)
    {
        $bracket = GroupBracket::findOne($bracketId);

        if ($bracket->tournament->type->team_mode) {
            $duels = TeamDuel::find()->alias('d')->groupBy(['d.id'])
                ->select('d.*, g.title as group_title, d.team_one_id as id_1, d.team_two_id as id_2, t1.name as name_1, t2.name as name_2')
                ->leftJoin(TournamentToTeam::tableName() . ' ttt1', 'ttt1.id = d.team_one_id')
                ->leftJoin(Team::tableName() . ' t1', 't1.id = ttt1.team_id')
                ->leftJoin(TournamentToTeam::tableName() . ' ttt2', 'ttt2.id = d.team_two_id')
                ->leftJoin(Team::tableName() . ' t2', 't2.id = ttt2.team_id')
                ->innerJoin(Round::tableName() . ' r', 'r.id = d.round_id')
                ->innerJoin(Group::tableName() . ' g', 'g.id = d.group_id')
                ->where(['r.bracket_id' => $bracketId])
                ->orderBy('d.id')
                ->asArray()
                ->indexBy('id')
                ->all();
        } else {
            $duels = PlayerDuel::find()->alias('d')->groupBy(['d.id'])
                ->select('d.*, g.title as group_title, d.player_one_id as id_1, d.player_two_id as id_2, p1.nick as name_1, p2.nick as name_2, p1.external_link as external_link_1, p2.external_link as external_link_2')
                ->leftJoin(TournamentToPlayer::tableName() . ' ttp1', 'ttp1.id = d.player_one_id')
                ->leftJoin(Player::tableName() . ' p1', 'p1.id = ttp1.player_id')
                ->leftJoin(TournamentToPlayer::tableName() . ' ttp2', 'ttp2.id = d.player_two_id')
                ->leftJoin(Player::tableName() . ' p2', 'p2.id = ttp2.player_id')
                ->innerJoin(Round::tableName() . ' r', 'r.id = d.round_id')
                ->innerJoin(Group::tableName() . ' g', 'g.id = d.group_id')
                ->where(['r.bracket_id' => $bracketId])
                ->orderBy('d.id')
                ->asArray()
                ->indexBy('id')
                ->all();
        }

        $result = [];
        foreach ($duels as $duel) {
            //if ($duel['id_1']) {
            if($bracket->best_of > 1){
                if (!isset($result[$duel['id_1']]['play'])) $result[$duel['id_1']]['play'] = 0;
                if (!isset($result[$duel['id_1']]['win'])) $result[$duel['id_1']]['win'] = 0;
                if (!isset($result[$duel['id_1']]['lose'])) $result[$duel['id_1']]['lose'] = 0;
                if (!isset($result[$duel['id_1']]['tie'])) $result[$duel['id_1']]['tie'] = 0;
                if (!isset($result[$duel['id_1']]['points'])) $result[$duel['id_1']]['points'] = 0;
                $result[$duel['id_1']]['id'] = intval($duel['id_1']);
                $result[$duel['id_1']]['name'] = $duel['name_1'];
                $result[$duel['id_1']]['external_link'] = $duel['external_link_1'];
                $result[$duel['id_1']]['group_id'] = $duel['group_id'];
                $result[$duel['id_1']]['group_title'] = $duel['group_title'];

                if (!$duel['active']) {
                    $result[$duel['id_1']]['play'] += 1;

                    if (!$duel['winner_id']) { // ничья
                        $result[$duel['id_1']]['tie'] += 1;
                        $result[$duel['id_1']]['points'] += 1;
                    } elseif ($duel['winner_id'] == $duel['id_1']) { // выиграл
                        $result[$duel['id_1']]['win'] += 1;
                        $result[$duel['id_1']]['points'] += 3;
                    } elseif ($duel['loser_id'] == $duel['id_1']) { // проиграл
                        $result[$duel['id_1']]['lose'] += 1;
                    }
                }
            //}

            //if ($duel['id_2']) {
                if (!isset($result[$duel['id_2']]['play'])) $result[$duel['id_2']]['play'] = 0;
                if (!isset($result[$duel['id_2']]['win'])) $result[$duel['id_2']]['win'] = 0;
                if (!isset($result[$duel['id_2']]['lose'])) $result[$duel['id_2']]['lose'] = 0;
                if (!isset($result[$duel['id_2']]['tie'])) $result[$duel['id_2']]['tie'] = 0;
                if (!isset($result[$duel['id_2']]['points'])) $result[$duel['id_2']]['points'] = 0;
                $result[$duel['id_2']]['id'] = intval($duel['id_2']);
                $result[$duel['id_2']]['name'] = $duel['name_2'];
                $result[$duel['id_2']]['external_link'] = $duel['external_link_2'];
                $result[$duel['id_2']]['group_id'] = $duel['group_id'];
                $result[$duel['id_2']]['group_title'] = $duel['group_title'];

                if (!$duel['active']) {
                    $result[$duel['id_2']]['play'] += 1;

                    if (!$duel['winner_id']) { // ничья
                        $result[$duel['id_2']]['tie'] += 1;
                        $result[$duel['id_2']]['points'] += 1;
                    } elseif ($duel['winner_id'] == $duel['id_2']) { // выиграл
                        $result[$duel['id_2']]['win'] += 1;
                        $result[$duel['id_2']]['points'] += 3;
                    } elseif ($duel['loser_id'] == $duel['id_2']) { // проиграл
                        $result[$duel['id_2']]['lose'] += 1;
                    }
                }
            }else{
                if (!isset($result[$duel['id_1']]['play'])) $result[$duel['id_1']]['play'] = 0;
                if (!isset($result[$duel['id_1']]['win'])) $result[$duel['id_1']]['win'] = 0;
                if (!isset($result[$duel['id_1']]['lose'])) $result[$duel['id_1']]['lose'] = 0;
                if (!isset($result[$duel['id_1']]['tie'])) $result[$duel['id_1']]['tie'] = 0;
                if (!isset($result[$duel['id_1']]['points'])) $result[$duel['id_1']]['points'] = 0;
                $result[$duel['id_1']]['id'] = intval($duel['id_1']);
                $result[$duel['id_1']]['name'] = $duel['name_1'];
                $result[$duel['id_1']]['external_link'] = $duel['external_link_1'];
                $result[$duel['id_1']]['group_id'] = $duel['group_id'];
                $result[$duel['id_1']]['group_title'] = $duel['group_title'];

                if (!$duel['active']) {
                    $result[$duel['id_1']]['play'] += 1;

                    if (!$duel['winner_id']) { // ничья
                        $result[$duel['id_1']]['tie'] += 1;
                        $result[$duel['id_1']]['points'] += 0;
                    } elseif ($duel['winner_id'] == $duel['id_1']) { // выиграл
                        $result[$duel['id_1']]['win'] += 1;
                        $result[$duel['id_1']]['points'] += 1;
                    } elseif ($duel['loser_id'] == $duel['id_1']) { // проиграл
                        $result[$duel['id_1']]['lose'] += 1;
                    }                
            }
            //}
        }

        $grouped = [];
        foreach ($result as $partId => $partData) {
            $grouped[$partData['group_id']]['title'] = $partData['group_title'];
            $grouped[$partData['group_id']]['participants'][$partId] = $partData;
        }

        return $grouped;
    }
}
