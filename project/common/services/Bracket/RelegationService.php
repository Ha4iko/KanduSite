<?php

namespace common\services\Bracket;

use common\models\Bracket\Relegation;
use common\models\Bracket\Relegation\PlayerDuel;
use common\models\Bracket\Relegation\Round;
use common\models\Bracket\Relegation\TeamDuel;
use common\services\TournamentService;
use yii\base\InvalidConfigException;

/**
 * Class RelegationService
 * @package common\services\Bracket
 */
class RelegationService
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
    private $doubleElimination;

    /**
     * @var bool
     */
    private $thirdPlace;

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
     * @param bool $doubleElimination
     * @param bool $thirdPlace
     * @throws \Throwable
     */
    public function create($bracketId, $participantsCount, $teamsMode, $doubleElimination = false, $thirdPlace = false) {
        if (!$bracketId || !$participantsCount || !is_bool($teamsMode)) {
            throw new InvalidConfigException(json_encode([$bracketId, $participantsCount, $teamsMode]));
        }
        if (!in_array($participantsCount, [4, 8, 16, 32, 64, 128])) {
            throw new InvalidConfigException();
        }
        $this->participantsCount = $participantsCount;
        $this->teamsMode = $teamsMode;
        $this->bracketId = $bracketId;
        $this->doubleElimination = $doubleElimination;
        $this->thirdPlace = $thirdPlace;
        $lastIndex = $this->createMainBracket();
        if ($doubleElimination) {
            $lastIndex = $this->createDefeatBracket($lastIndex);
        }
        $this->createGrandFinalBracket($lastIndex);
        $this->makeLinks();
    }

    /**
     * @param int $bracketId
     * @throws \Throwable
     */
    public function clearAll(int $bracketId)
    {
        $bracket = Relegation::findOne($bracketId);
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
     * @throws \Exception
     */
    private function createMainBracket() {
        $duelsCount = $this->participantsCount / 2;
        $tour = 1;
        $index = 0;
        $min = $this->doubleElimination ? 1 : 2;

        while ($duelsCount >= $min) {
            $round = Round::findOrCreate($this->bracketId, $tour, Round::TYPE_MAIN);
            for ($i = 0; $i < $duelsCount; $i++) {
                $this->createDuel($round->id, $tour, $index++);
            }
            $tour++;
            $duelsCount = $duelsCount / 2;
        }
        return $index;
    }

    /**
     * @param $index
     * @return mixed
     * @throws \Exception
     */
    private function createDefeatBracket($index) {
        $tour = 2;
        $duelsCount = $this->participantsCount / 2 / 2;

        while ($duelsCount >= 1) {
            for ($n = 0; $n < 2; $n++) {
                $round = Round::findOrCreate($this->bracketId, $tour, Round::TYPE_DEFEAT, $tour - 1 . 'th round');
                for ($i = 0; $i < $duelsCount; $i++) {
                    $this->createDuel($round->id, $tour, $index++);
                }
                $tour++;
            }
            $duelsCount = $duelsCount / 2;
        }

        return $index;
    }

    /**
     * @param $index
     * @return mixed
     * @throws \Exception
     */
    private function createGrandFinalBracket($index) {
        $tour = null;

        $titles = [
            '1st place game 1st round',
        ];

        if ($this->doubleElimination) {
            $titles[] = '1st place game 2nd round';
        }

        if (!$this->doubleElimination) {
            $titles[0] = '1st place game';
        }

        if (!$this->doubleElimination && $this->thirdPlace) {
            array_unshift($titles, '3rd place game');
        }

        for ($n = 0; $n < count($titles); $n++) {
            $round = Round::findOrCreate($this->bracketId, $n + 1, Round::TYPE_GRAND, $titles[$n]);
            $this->createDuel($round->id, $tour, $index++);
        }

        return $index;
    }

    /**
     * @param $roundId
     * @param $tour
     * @param $index
     * @throws \Exception
     */
    private function createDuel($roundId, $tour, $index) {
        $duel = $this->duelFactory();
        $duel->load([
            'round_id' => $roundId,
            'level' => $tour,
            'order' => $index,
        ], '');
        if (!$duel->save()) {
            throw new \Exception('Duel not saved');
        }
    }

    /**
     * @param $bracketId
     * @param $playerIds
     * @return int
     * @throws \Exception
     */
    public function fillFirstRound($bracketId, $playerIds) {
        $bracket = Relegation::findOne($bracketId);
        if (!$bracket) {
            throw new \Exception('Bracket not found');
        }
        $playerIds = array_slice($playerIds, 0, $bracket->participants);
        foreach ($playerIds as $playerId) {
            [$duel, $field] = $this->findNextFreeDuel($bracket, Round::TYPE_MAIN, 1);
            if ($duel) {
                $duel[$field] = $playerId;
                if (!$duel->save()) {
                    throw new \Exception('Duel not saved');
                }
            }
        }
        return count($playerIds);
    }

    /**
     * Generate array of tournament brackets with relations
     *
     * @param null $playersCount
     * @return array
     */
    public function generate($playersCount = null) {
        $generator = new RelegationGeneratorService();
        return $generator->generate($this->participantsCount ?? $playersCount, $this->doubleElimination, $this->thirdPlace);
    }

    /**
     * Make relations between duels
     */
    private function makeLinks() {
        $generated = $this->generate();
        $bracket = Relegation::findOne($this->bracketId);
        $duels = $bracket->getDuels();

        foreach ($generated as $i => $item) {
            $duels[$i]->winner_to_duel_id = $duels[$item['winner_index']]->id;
            $duels[$i]->loser_to_duel_id = $duels[$item['loser_index']]->id;
            $duels[$i]->save();
        }
    }

    /**
     * @param Relegation $bracket
     * @param int $roundNumber
     * @param $type
     * @return array|null
     */
    private function findNextFreeDuel(Relegation $bracket, $type, $roundNumber = null) {
        $duels = $bracket->getDuels($type, $roundNumber);
        foreach ($duels as $i => $duel) {
            if (!$duel->player_1) {
                return [$duel, 'player_1'];
            }
            if (!$duel->player_2) {
                return [$duel, 'player_2'];
            }
        }
        return null;
    }


    /**
     * @param int $bracketId RelegationBracket id
     * @return array
     */
    public function getBracketParticipantsList(int $bracketId)
    {
        $bracket = Relegation::findOne($bracketId);
        return $this->tournamentService->getParticipantsList($bracket->tournament_id);
    }

    /**
     * @param int $bracketId
     * @param array $participantIds
     * @throws \Throwable
     */
    public function attachParticipantsToBracket(int $bracketId, array $participantIds)
    {
        shuffle($participantIds);
        $participantIds = array_values($participantIds);
        $this->fillFirstRound($bracketId, $participantIds);
    }
}