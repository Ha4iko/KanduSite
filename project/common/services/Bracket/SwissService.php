<?php

namespace common\services\Bracket;

use common\models\Bracket\Swiss as SwissBracket;
use common\models\Bracket\Swiss\PlayerDuel;
use common\models\Bracket\Swiss\Round;
use common\models\Bracket\Swiss\TeamDuel;
use common\models\Player;
use common\models\Team;
use common\models\TournamentToPlayer;
use common\models\TournamentToTeam;
use common\services\TournamentService;
use yii\base\InvalidConfigException;
use yii\base\UserException;

/**
 * Class SwissService
 * @package common\services\Bracket
 */
class SwissService
{
    /**
     * @var int
     */
    private $bracketId;

    /**
     * @var int
     */
    private $participantsCount;

    /**
     * @var bool
     */
    private $teamsMode;

    /**
     * @var bool
     */
    private $isEven;

    /**
     * @var int
     */
    private $countRounds;

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
     * @param $bracketId
     * @param $participantsCount
     * @param $teamsMode
     * @param int $countRounds
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function create($bracketId, $participantsCount, $teamsMode, $countRounds) {
        if (!$bracketId || !$participantsCount || !is_bool($teamsMode)) {
            throw new InvalidConfigException(json_encode([$bracketId, $participantsCount, $teamsMode]));
        }

        $this->participantsCount = $participantsCount;
        $this->teamsMode = $teamsMode;
        $this->bracketId = $bracketId;
        $this->isEven = !($this->participantsCount % 2);
        $this->countRounds = $countRounds;
        $this->createRounds($countRounds);
    }


    /**
     * @param int $bracketId
     * @throws \Throwable
     */
    public function clearAll(int $bracketId)
    {
        $bracket = SwissBracket::findOne($bracketId);
        foreach ($bracket->rounds as $round) {
            $round->delete();
        }

    }

    /**
     * @return PlayerDuel|TeamDuel
     */
    private function duelFactory() {
        if ($this->teamsMode) {
            return new TeamDuel();
        } else {
            return new PlayerDuel();
        }
    }

    /**
     * @param int $n count of rounds
     * @throws \Exception
     */
    private function createRounds($n) {
        if ($n < 1 || $n > 10) {
            throw new \Exception('Invalid count of rounds');
        }

        // пока не удаляем.. вдруг пригодится еще :)
        /*$n = 5;

        if ($this->participantsCount <= 8) {
            $n = 5;
        } elseif ($this->participantsCount <= 16) {
            $n = 6;
        } elseif ($this->participantsCount <= 32) {
            $n = 7;
        } elseif ($this->participantsCount <= 64) {
            $n = 8;
        }

        if ($this->additionalRounds) {
            $n += $this->additionalRounds;
        }*/

        for ($i = 0; $i < $n; $i++) {
            $round = Round::findOrCreate($this->bracketId, $i + 1);
            $duelsCount = ceil($this->participantsCount / 2);
            for ($j = 0; $j < $duelsCount; $j++) {
                $this->createDuel($round->id, $j + 1);
            }
        }
    }

    /**
     * @param $roundId
     * @param $order
     * @throws \Exception
     */
    private function createDuel($roundId, $order) {
        $duel = $this->duelFactory();
        $duel->load([
            'round_id' => $roundId,
            'order' => $order,
        ], '');
        if (!$duel->save()) {
            throw new \Exception('Duel not saved');
        }
    }


    public function generatePairs($roundId) {
        $round = Round::findOne($roundId);
        $standings = $this->getStandings($round->bracket_id);

        // cleanup players
        $duels = $round->bracket->getDuels($round->order);
        foreach ($duels as $duel1) {
            $duel1->player_1 = null;
            $duel1->player_2 = null;
            $duel1->save();
        }

        // get player pairs
        $allDuels = $round->bracket->getDuels();
        $allPlayedPairs = [];
        foreach ($allDuels as $duel2) {
            if ($duel2->player_1 || $duel2->player_2) {
                $pair = [$duel2->player_1, $duel2->player_2];
                sort($pair);
                $allPlayedPairs[] = implode('-', $pair);
            }
        }

        // try to get pairs by rating groups
        $groups = [];
        foreach ($standings as $key => $item) {
            if (!$item['id']) {
                continue;
            }
            $groups[$item['points']][] = $item['id'];
        }

        krsort($groups, SORT_NUMERIC);
        $groups = array_values($groups);

        foreach ($groups as $i => &$group) {
            if (count($group) % 2 !== 0) {
                if ($groups[$i + 1]) {
                    $groups[$i + 1][] = array_pop($group);
                }
            }
        }

        if (count($groups[count($groups) - 1]) % 2 !== 0) {
            $groups[count($groups) - 1][] = null;
        }

        $allDuels = $round->bracket->getDuels();

        $pairs = [];
        $playedPairs = [];

        foreach ($groups as $group2) {
            if (empty($group2)) {
                continue;
            }
            $newPairs = [];
            for ($i = 0; $i < count($group2) / 2; $i++) {
                $newPair = [$group2[$i], $group2[$i + count($group2) / 2]];

                foreach ($allDuels as $duel) {
                    if (
                        ($duel->player_1 === $newPair[0] && $duel->player_2 === $newPair[1]) ||
                        ($duel->player_1 === $newPair[1] && $duel->player_2 === $newPair[0])
                    ) {
                        $playedPairs[] = $newPair;
                    }
                }
                $newPairs[] = $newPair;
            }
            $pairs = array_merge($pairs, $newPairs ?? []);
        }


        // try to get unique player combinations
        if (!empty($playedPairs)) {

            $group = array_filter(array_filter(array_column($standings, 'id')));
            if (count($group) % 2 !== 0) {
                $group[] = null;
            }

            $combinations = [];

            for ($i = 0; $i < count($group); $i++) {
                for ($j = count($group) - 1; $j >= 0; $j--) {

                    if ($group[$i] === $group[$j]) {
                        continue;
                    }
                    $pair = [$group[$j], $group[$i]];
                    sort($pair);
                    $combinations[] = implode('-', $pair);
                }
            }

            $combinations = array_values(array_unique($combinations));
            $availableCombinations = array_values(array_diff($combinations, $allPlayedPairs));
            rsort($availableCombinations);
            $maxPairs = count($group) / 2;

            $pairs = [];
            $i = 0;

            while (count($pairs) < $maxPairs) {
                $pairs = [];
                shuffle($availableCombinations);


                foreach ($availableCombinations as $combination) {
                    if (count($pairs) === $maxPairs) {
                        break;
                    }
                    $ids = array_map('intval', explode('-', $combination));
                    if (empty(array_intersect(array_merge([], ...$pairs), $ids))) {
                        if ($ids[0] === 0) {
                            rsort($ids);
                        }
                        $pairs[] = $ids;
                    }
                }
                if ($i++ > 1000) {
                    break;
                }
            }

            if (count($pairs) !== $maxPairs) {
                throw new UserException('Pairs can not be generated');
            }
        }

        if (empty($pairs)) {
            throw new UserException('Pairs can not be generated');
        }

        usort($pairs, function($a, $b) {
            return $a[1] > $b[1] ? -1 : 1;
        });

        $duels = $round->bracket->getDuels($round->order);

        foreach ($pairs as $i => $pair) {
            $duels[$i]->player_1 = $pair[0] ?: null;
            $duels[$i]->player_2 = $pair[1] ?: null;
            $duels[$i]->save();
        }
    }

    /**
     * @param int $bracketId
     * @param array $participantIds
     * @throws \Throwable
     */
    public function attachParticipantsToBracket(int $bracketId, array $participantIds)
    {
        $bracket = SwissBracket::findOne($bracketId);

        shuffle($participantIds);

        // clear
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

        // fill first round
        foreach ($bracket->getDuels(1) as $duel) {
            $duel->player_1 = null;
            $duel->player_2 = null;
            $duel->winner_id = null;
            $duel->loser_id = null;
            $duel->score_one = null;
            $duel->score_two = null;
            $duel->active = 1;

            $participantId = array_shift($participantIds);
            if ($participantId) {
                $duel->player_1 = $participantId;
            }

            $participantId = array_shift($participantIds);
            if ($participantId) {
                $duel->player_2 = $participantId;
            }

            if (!$duel->save()) {
                throw new \Exception('Duel not saved');
            }
        }

        $bracket->editable_scores = 1;
        if (!$bracket->save()) {
            throw new \Exception('Bracket not saved');
        }
    }


    /**
     * @param int $bracketId SwissBracket id
     * @return array
     */
    public function getBracketParticipantsList(int $bracketId)
    {
        $bracket = SwissBracket::findOne($bracketId);
        return $this->tournamentService->getParticipantsList($bracket->tournament_id);
    }

    /**
     * @param int $bracketId GroupBracket id
     * @return array
     */
    public function getStandings(int $bracketId)
    {
        $bracket = SwissBracket::findOne($bracketId);

        if ($bracket->tournament->type->team_mode) {
            $duels = TeamDuel::find()->alias('d')->groupBy(['d.id'])
                ->select('d.*, d.team_one_id as id_1, d.team_two_id as id_2, t1.name as name_1, t2.name as name_2')
                ->leftJoin(TournamentToTeam::tableName() . ' ttt1', 'ttt1.id = d.team_one_id')
                ->leftJoin(Team::tableName() . ' t1', 't1.id = ttt1.team_id')
                ->leftJoin(TournamentToTeam::tableName() . ' ttt2', 'ttt2.id = d.team_two_id')
                ->leftJoin(Team::tableName() . ' t2', 't2.id = ttt2.team_id')
                ->innerJoin(Round::tableName() . ' r', 'r.id = d.round_id')
                ->where(['r.bracket_id' => $bracketId])
                ->orderBy('d.id')
                ->asArray()
                ->indexBy('id')
                ->all();
        } else {
            $duels = PlayerDuel::find()->alias('d')->groupBy(['d.id'])
                ->select('d.*, d.player_one_id as id_1, d.player_two_id as id_2, p1.nick as name_1, p2.nick as name_2, p1.external_link as external_link_1, p2.external_link as external_link_2')
                ->leftJoin(TournamentToPlayer::tableName() . ' ttp1', 'ttp1.id = d.player_one_id')
                ->leftJoin(Player::tableName() . ' p1', 'p1.id = ttp1.player_id')
                ->leftJoin(TournamentToPlayer::tableName() . ' ttp2', 'ttp2.id = d.player_two_id')
                ->leftJoin(Player::tableName() . ' p2', 'p2.id = ttp2.player_id')
                ->innerJoin(Round::tableName() . ' r', 'r.id = d.round_id')
                ->where(['r.bracket_id' => $bracketId])
                ->orderBy('d.id')
                ->asArray()
                ->indexBy('id')
                ->all();
        }

        $result = [];
        foreach ($duels as $duel) {
            $fields = ['id_1', 'id_2'];

            foreach ($fields as $field) {
                if ($duel[$field]) {
                    if (!isset($result[$duel[$field]]['play'])) $result[$duel[$field]]['play'] = 0;
                    if (!isset($result[$duel[$field]]['win'])) $result[$duel[$field]]['win'] = 0;
                    if (!isset($result[$duel[$field]]['lose'])) $result[$duel[$field]]['lose'] = 0;
                    if (!isset($result[$duel[$field]]['tie'])) $result[$duel[$field]]['tie'] = 0;
                    if (!isset($result[$duel[$field]]['points'])) $result[$duel[$field]]['points'] = 0;
                    $result[$duel[$field]]['id'] = intval($duel[$field]);
                    $result[$duel[$field]]['name'] = $duel['name_' . $field[strlen($field) - 1]];
                    $result[$duel[$field]]['external_link'] = $duel['external_link_' . $field[strlen($field) - 1]];


                    if($bracket->best_of > 1){

                    if ($duel['active'] === '0') {
                        $result[$duel[$field]]['play'] += 1;

                        if (!$duel['winner_id']) { // ничья
                            $result[$duel[$field]]['tie'] += 1;
                            $result[$duel[$field]]['points'] += 1;
                        } elseif ($duel['winner_id'] == $duel[$field]) { // выиграл
                            $result[$duel[$field]]['win'] += 1;
                            $result[$duel[$field]]['points'] += 3;
                        } elseif ($duel['loser_id'] == $duel[$field]) { // проиграл
                            $result[$duel[$field]]['lose'] += 1;
                        }
                    }

                    if ($duel['active'] === '1') {
                        if (($duel['name_1'] && !$duel['name_2']) || ($duel['name_2'] && !$duel['name_1'])) {
                            $result[$duel[$field]]['points'] += 3;
                        }
                    }
                }else{
                    if ($duel['active'] === '0') {
                        $result[$duel[$field]]['play'] += 1;

                        if (!$duel['winner_id']) { // ничья
                            $result[$duel[$field]]['tie'] += 1;
                            $result[$duel[$field]]['points'] += 0;
                        } elseif ($duel['winner_id'] == $duel[$field]) { // выиграл
                            $result[$duel[$field]]['win'] += 1;
                            $result[$duel[$field]]['points'] += 1;
                        } elseif ($duel['loser_id'] == $duel[$field]) { // проиграл
                            $result[$duel[$field]]['lose'] += 1;
                        }
                    }

                    if ($duel['active'] === '1') {
                        if (($duel['name_1'] && !$duel['name_2']) || ($duel['name_2'] && !$duel['name_1'])) {
                            $result[$duel[$field]]['points'] += 1;
                        }
                    }
                }
                }
            }
            
        }

        return $result;
    }
}
