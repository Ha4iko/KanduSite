<?php

namespace frontend\models;

use common\models\PlayerWorld;
use Yii;
use yii\base\Exception;

/**
 * @property array $participantsRelationsWithTeamsGroupedByTeam
 * @property array $participantsRelationsGroupedByTeam
 */
class ParticipantsWithTeamsForm extends Tournament
{
    /**
     * @return array
     */
    public function getParticipantsRelationsWithTeamsGroupedByTeam()
    {
        $participantsRelationsGroupedByTeam = $this->getParticipantsRelationsGroupedByTeam();
        $teamIds = array_keys($participantsRelationsGroupedByTeam);

        foreach ($this->tournamentToTeam as $link) {
            if (in_array($link->team_id, $teamIds)) continue;

            $participantsRelationsGroupedByTeam[$link->team_id] = $link;
        }

        return $participantsRelationsGroupedByTeam;
    }

    /**
     * @return array
     */
    public function getParticipantsRelationsGroupedByTeam()
    {
        $participantsRelationsGroupedByTeam = [];
        foreach ($this->tournamentToPlayer as $link) {
            if (!$link->team_id) continue;
            $participantsRelationsGroupedByTeam[$link->team_id][] = $link;
        }

        return $participantsRelationsGroupedByTeam;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    public function prepareData(array &$data)
    {
        // clear trash
        unset($data['teamNamesByOffset'][0]);
        unset($data['TournamentToPlayer'][0]);
        unset($data['TournamentToPlayer']['%i%']);

        $teamNamesByOffset = $data['teamNamesByOffset'] ?? [];

        // set team name from common team fields to each player
        if (is_array($teamNamesByOffset) && count($teamNamesByOffset)) {
            if (isset($data['TournamentToPlayer']) && is_array($data['TournamentToPlayer'])) {
                foreach ($data['TournamentToPlayer'] as &$link) {
                    $linkTeamOffsetId = intval($link['postTeamOffset'] ?? 0);
                    if ($linkTeamOffsetId && isset($teamNamesByOffset[$linkTeamOffsetId]['name'])) {
                        $link['teamName'] = trim($teamNamesByOffset[$linkTeamOffsetId]['name']);
                    }
                }
            }
        }

        // create team form data
        $teamsToProcess = [];
        if (is_array($teamNamesByOffset) && count($teamNamesByOffset)) {
            foreach ($teamNamesByOffset as $teamData) {
                $linkId = intval($teamData['id']);
                $teamName = trim($teamData['name']);
                if (!$teamName) continue;
                $teamsToProcess[$teamName] = $linkId ?: null;
            }
        }

        $data['TournamentToTeam'] = [];
        foreach ($teamsToProcess as $teamNameToProcess => $linkIdToProcess) {
            $data['TournamentToTeam'][] = [
                'id' => $linkIdToProcess,
                'tournament_id' => $this->id,
                'teamName' => $teamNameToProcess,
            ];
        }

        $this->createPlayersIfNotExist($data['TournamentToPlayer'] ?? []);
        $this->createWorldsIfNotExist($data['TournamentToPlayer'] ?? []);
        $this->createTeamsIfNotExist($data['TournamentToPlayer'] ?? []);
        $this->createTeamsIfNotExist($data['TournamentToTeam'] ?? []);
    }


    /**
     * @param array $postLinks
     * @throws Exception
     */
    protected function createPlayersIfNotExist(array $postLinks)
    {
        foreach ($postLinks as $postLink) {
            $nick = trim($postLink['playerNick'] ?? '');
            if (!$nick) continue;

            if (null === Player::findOne(['nick' => $nick])) {
                $player = new Player();
                $player->loadDefaultValues();
                $player->nick = $nick;
                $player->loadAvatar();
                if (!$player->save()) {
                    throw new Exception('Creation player is failed');
                }
            }
        }
    }

    /**
     * @param array $postLinks
     * @throws Exception
     */
    protected function createTeamsIfNotExist(array $postLinks)
    {
        foreach ($postLinks as $postLink) {
            $teamName = trim($postLink['teamName'] ?? '');
            if (!$teamName) continue;

            if (null === Team::findOne(['name' => $teamName])) {
                $team = new Team();
                $team->loadDefaultValues();
                $team->name = $teamName;
                if (!$team->save()) {
                    throw new Exception('Creation team is failed');
                }
            }
        }
    }

    /**
     * @param array $postLinks
     * @throws Exception
     */
    protected function createWorldsIfNotExist(array $postLinks)
    {
        foreach ($postLinks as $postLink) {
            $name = trim($postLink['worldName'] ?? '');
            if (!$name) continue;

            if (null === PlayerWorld::findOne(['name' => $name])) {
                $world = new PlayerWorld();
                $world->loadDefaultValues();
                $world->name = $name;
                if (!$world->save()) {
                    throw new Exception('Creation world is failed');
                }
            }
        }
    }

}
