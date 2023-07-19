<?php

namespace common\services;

use common\models\PlayerClass;
use common\models\TournamentPrize;
use frontend\models\Player;
use frontend\models\Team;
use frontend\models\TournamentForm;
use frontend\models\TournamentToPlayer;
use frontend\models\TournamentToTeam;
use Yii;
use frontend\models\Tournament;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class TournamentService
{
    /**
     * @param int $limit
     * @param array $excludeIds
     * @param array $conditions
     * @return Tournament[]|array
     */
    public function getLatestTournaments($limit = 6, $excludeIds = [], $conditions = [])
    {
        $query = Tournament::find()
            // ->where(['status' => Tournament::STATUS_IN_PROGRESS])
            ->orderBy(['date' => SORT_DESC, 'id' => SORT_DESC])
            ->limit($limit);

        if (Yii::$app->user->isGuest) {
            $query->andWhere(['<>', 'status', Tournament::STATUS_PENDING]);
        }

        if ($excludeIds) {
            $query->andWhere(['NOT IN', 'id', $excludeIds]);
        }

        if ($conditions) {
            $query->andWhere($conditions);
        }

        return $query->all();
    }

    /**
     * @param string $slug
     * @return Tournament|ActiveRecord|null
     */
    public function getTournament($slug)
    {
        $query = Tournament::find()
            ->where(['slug' => $slug]);

        return $query->one();
    }

    /**
     * @param $id
     * @return Tournament|ActiveRecord|null
     */
    public function getTournamentById($id)
    {
        $query = Tournament::find()
            ->where(['id' => $id]);

        return $query->one();
    }

    /**
     * @param TournamentForm $form
     * @return boolean|string slug of new tournament
     * @throws \Exception
     */
    public function createTournament(TournamentForm $form)
    {
        $model = new Tournament();

        if ($model->load($form->attributes, '')) {
            $model->status = Tournament::STATUS_PENDING;
            $model->pool = 0;
            $model->organizer_id = Yii::$app->user->getId() ?: null;

            if ($model->save()) {
                return $model->slug;
            } else {
                Yii::error('Tournament not created: ' . implode(';', $model->getErrorSummary(true)));
                throw new \Exception('Tournament not created');
            }
        }

        return false;
    }

    /**
     * @param TournamentForm $form
     * @return boolean
     * @throws \Exception
     */
    public function updateTournament(TournamentForm $form)
    {
        if (!$form->id) return false;

        $model = Tournament::findOne($form->id);

        if (!is_object($model)) return false;

        if ($model->load($form->attributes, '')) {
            if ($model->save()) {
                return true;
            } else {
                Yii::error('Tournament not created: ' . implode(';', $model->getErrorSummary(true)));
                throw new \Exception('Tournament not created');
            }
        }

        return false;
    }

    /**
     * Get participants list [[id => name]]
     *
     * @param $tournamentId
     * @return array
     */
    public function getParticipantsList($tournamentId) {
        $tournament = $this->getTournamentById($tournamentId);
        $participants = [];
        if ($tournament->type->team_mode) {
            foreach ($tournament->tournamentToTeam as $tt) {
                $participants[$tt->id] = $tt->team->name;
            }
        } else {
            foreach ($tournament->tournamentToPlayer as $tp) {
                $participants[$tp->id] = $tp->player->name;
            }
        }
        return $participants;
    }

    /**
     * @param Tournament $tournament
     * @param bool $withEmptyPlayers
     * @return array
     */
    public function getParticipantsData(Tournament $tournament, bool $withEmptyPlayers = false)
    {
        $participants = [];
        foreach ($tournament->tournamentToPlayer as $tournamentToPlayer) {
            if (!is_object($tournamentToPlayer)) continue;
            if (!$withEmptyPlayers && !$tournamentToPlayer->player_id) continue;
            $player = Player::findOne($tournamentToPlayer->player_id);
            if (!$withEmptyPlayers && !is_object($player)) continue;

            $participants[$tournamentToPlayer->id]['player'] = $player;
            $participants[$tournamentToPlayer->id]['params'] = $tournamentToPlayer;
        }
        return $participants;
    }

    /**
     * @param Tournament $tournament
     * @param bool $withEmptyPlayers
     * @return array
     */
    public function getParticipantsWithTeams(Tournament $tournament, bool $withEmptyPlayers = false)
    {
        $participants = $this->getParticipantsData($tournament, $withEmptyPlayers);
        $groupedByTeams = [];
        foreach ($participants as $linkId => $participant) {
            $teamId = intval($participant['params']->team_id);
            if (!$teamId) continue;
            $team = Team::findOne($teamId);
            if (!is_object($team)) continue;

            $groupedByTeams[$teamId]['team'] = $team;
            $groupedByTeams[$teamId]['players'][$linkId]['player'] = $participant['player'];
            $groupedByTeams[$teamId]['players'][$linkId]['params'] = $participant['params'];
        }

        $teamIds = array_keys($groupedByTeams);
        foreach ($tournament->tournamentToTeam as $linkTeam) {
            if (in_array($linkTeam->team_id, $teamIds)) continue;

            $team = Team::findOne($linkTeam->team_id);
            if (!is_object($team)) continue;

            $groupedByTeams[$linkTeam->team_id]['team'] = $team;
            $groupedByTeams[$linkTeam->team_id]['players'] = [];
            $groupedByTeams[$linkTeam->team_id]['params'] = $linkTeam;
        }

        return $groupedByTeams;
    }

    /**
     * @param string $target target for winners: player, team
     * @param int|string $tournamentId
     * @param bool $withEmpty
     * @return array
     * @throws Exception
     */
    public function getWinnersStandard(string $target, $tournamentId, bool $withEmpty = false)
    {
        $tournament = Tournament::findOne($tournamentId);
        if (!is_object($tournament)) {
            throw new Exception('Wrong tournament id');
        }

        if (!in_array($target, ['player', 'team'])) {
            throw new Exception('Wrong target');
        }

        if ($target === 'player') {
            $targetClass = TournamentToPlayer::class;
            $selectClass = Player::class;
            $targetAttributeLink = 'player_id';
        } else {
            $targetClass = TournamentToTeam::class;
            $selectClass = Team::class;
            $targetAttributeLink = 'team_id';
        }

        $rewards = $targetClass::find()->asArray()
            ->select(['reward_base', $targetAttributeLink . ' as owner_id'])
            ->where(['tournament_id' => $tournament->id])
            ->andWhere('reward_base is not null')
            ->andWhere($targetAttributeLink . ' is not null')
            ->orderBy(['reward_base' => SORT_ASC])
            ->indexBy('reward_base')
            ->all();

        $winners = [];
        for ($i = 1; $i < 5; $i++) {
            if (isset($rewards[$i])) {

                if ($i === 1) {
                    $reward = trim($tournament->prize_one);
                    $description = trim($tournament->getAttributeLabel('prize_one'));
                } elseif ($i === 2) {
                    $reward = trim($tournament->prize_two);
                    $description = trim($tournament->getAttributeLabel('prize_two'));
                } elseif ($i === 3) {
                    $reward = trim($tournament->prize_three);
                    $description = trim($tournament->getAttributeLabel('prize_three'));
                } elseif ($i === 4) {
                    $reward = trim($tournament->prize_four);
                    $description = trim($tournament->getAttributeLabel('prize_four'));
                } else {
                    $reward = null;
                    $description = null;
                }

                if ($reward) {
                    $owner = $selectClass::findOne($rewards[$i]['owner_id']);
                    if ($owner) {
                        $winners[$i]['reward'] = $reward;
                        $winners[$i]['description'] = $description;
                        $winners[$i]['owner'] = $owner;
                    }
                }

            }
        }

        return $winners;
    }

    /**
     * @param string $target target for winners: player, team
     * @param int|string $tournamentId
     * @param bool $withEmpty
     * @param int $typeOfPrize example TournamentPrize::TYPE_SPECIAL
     * @return array
     * @throws Exception
     */
    public function getWinnersDynamic(string $target, $tournamentId, int $typeOfPrize = 0, bool $withEmpty = false)
    {
        if (!in_array($target, ['player', 'team'])) {
            throw new Exception('Wrong target');
        }

        if ($target === 'player') {
            $targetClass = TournamentToPlayer::class;
            $selectClass = Player::class;
            $targetAttributeLink = 'player_id';
        } else {
            $targetClass = TournamentToTeam::class;
            $selectClass = Team::class;
            $targetAttributeLink = 'team_id';
        }

        if ($typeOfPrize == TournamentPrize::TYPE_SPECIAL) {
            $query = $targetClass::find()->alias('ttp')->asArray()
                ->select(['reward_dyna', $targetAttributeLink . ' as owner_id'])
                ->where(['ttp.tournament_id' => $tournamentId])
                ->andWhere('reward_dyna is not null')
                ->andWhere($targetAttributeLink . ' is not null')
                ->indexBy('reward_dyna')
                ->innerJoin(TournamentPrize::tableName() . ' p', 'p.id = ttp.reward_dyna')
                ->andWhere(['p.type_id' => $typeOfPrize]);
        } elseif ($typeOfPrize == TournamentPrize::TYPE_SECONDARY) {
            $query = $targetClass::find()->alias('ttp')->asArray()
                ->select(['reward_dyna_sec', $targetAttributeLink . ' as owner_id'])
                ->where(['ttp.tournament_id' => $tournamentId])
                ->andWhere('reward_dyna_sec is not null')
                ->andWhere($targetAttributeLink . ' is not null')
                ->indexBy('reward_dyna_sec')
                ->innerJoin(TournamentPrize::tableName() . ' p', 'p.id = ttp.reward_dyna_sec')
                ->andWhere(['p.type_id' => $typeOfPrize]);
        } else {
            throw new \Exception('Unknown type of prize');
        }

        $rewards = $query->all();

        $winners = [];
        foreach ($rewards as $rewardId => $rewardRaw) {
            if ($typeOfPrize == TournamentPrize::TYPE_SPECIAL) {
                $reward = TournamentPrize::findOne($rewardRaw['reward_dyna']);
            } elseif ($typeOfPrize == TournamentPrize::TYPE_SECONDARY) {
                $reward = TournamentPrize::findOne($rewardRaw['reward_dyna_sec']);
            }
            $owner = $selectClass::findOne($rewardRaw['owner_id']);
            if ($reward && $owner && trim($reward->money)) {
                $winner['reward'] = $reward;
                $winner['owner'] = $owner;
                $winners[] = $winner;
            }
        }

        return $winners;

    }

    /**
     * @param int|string $tournamentId
     * @return Player[]
     */
    public function getPlayersWithoutRewards($tournamentId)
    {
        $query = Player::find()->alias('pl')
            ->select('pl.*, ttp.team_id')
            ->groupBy('pl.id')
            ->innerJoin( TournamentToPlayer::tableName() . ' ttp',
                'ttp.player_id = pl.id')
            ->where([
                'ttp.tournament_id' => $tournamentId,
            ])
            ->andWhere('reward_base is null')
            ->andWhere('reward_dyna is null')
            ->andWhere('reward_dyna_sec is null');

        return $query->all();
    }

    /**
     * @param int|string $tournamentId
     * @return Team[]
     */
    public function getTeamsWithoutRewards($tournamentId)
    {
        $query = Team::find()->alias('pl')
            ->groupBy('pl.id')
            ->innerJoin( TournamentToTeam::tableName() . ' ttp',
                'ttp.team_id = pl.id')
            ->where([
                'ttp.tournament_id' => $tournamentId,
            ])
            ->andWhere('reward_base is null')
            ->andWhere('reward_dyna is null')
            ->andWhere('reward_dyna_sec is null');

        return $query->all();
    }

    /**
     * @param int|string $tournamentId
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @return Player|null winner
     */
    public function getWinnerPlayer($tournamentId, $rewardBaseId = null, $rewardDynaId = null)
    {
        $rewardBaseId = intval($rewardBaseId);
        $rewardDynaId = intval($rewardDynaId);
        if ($rewardBaseId < 1 || $rewardBaseId > 4) {
            $rewardBaseId = 0;
        }

        if (!$rewardBaseId && !$rewardDynaId) return null;

        if ($rewardBaseId) { // base reward
            $link = TournamentToPlayer::findOne([
                'tournament_id' => $tournamentId,
                'reward_base' => $rewardBaseId,
            ]);

            if (null !== $link) {
                return Player::findOne($link->player_id);
            }

            return null;
        } else { // dynamic reward
            $link = TournamentToPlayer::findOne([
                'tournament_id' => $tournamentId,
                'reward_dyna' => $rewardDynaId,
            ]);

            if (null !== $link) {
                return Player::findOne($link->player_id);
            }

            return null;
        }
    }

    /**
     * @param int|string $tournamentId
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @param int|string $rewardDynaSecId
     * @return int|null winner
     */
    public function getWinnerParticipantPlayer($tournamentId, $rewardBaseId = null, $rewardDynaId = null, $rewardDynaSecId = null)
    {
        $rewardBaseId = intval($rewardBaseId);
        $rewardDynaId = intval($rewardDynaId);
        $rewardDynaSecId = intval($rewardDynaSecId);
        if ($rewardBaseId < 1 || $rewardBaseId > 4) {
            $rewardBaseId = 0;
        }

        if (!$rewardBaseId && !$rewardDynaId && !$rewardDynaSecId) return null;

        if ($rewardBaseId) { // base reward
            $link = TournamentToPlayer::findOne([
                'tournament_id' => $tournamentId,
                'reward_base' => $rewardBaseId,
            ]);

            if (null !== $link) {
                return $link->id;
            }

            return null;
        } elseif ($rewardDynaId) { // dynamic special reward
            $link = TournamentToPlayer::findOne([
                'tournament_id' => $tournamentId,
                'reward_dyna' => $rewardDynaId,
            ]);

            if (null !== $link) {
                return $link->id;
            }

            return null;
        } elseif ($rewardDynaSecId) { // dynamic secondary reward
            $link = TournamentToPlayer::findOne([
                'tournament_id' => $tournamentId,
                'reward_dyna_sec' => $rewardDynaSecId,
            ]);

            if (null !== $link) {
                return $link->id;
            }

            return null;
        }
        return null;
    }

    /**
     * @param int|string $tournamentId
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @return Team|null winner
     */
    public function getWinnerTeam($tournamentId, $rewardBaseId = null, $rewardDynaId = null)
    {
        $rewardBaseId = intval($rewardBaseId);
        $rewardDynaId = intval($rewardDynaId);
        if ($rewardBaseId < 1 || $rewardBaseId > 4) {
            $rewardBaseId = 0;
        }

        if (!$rewardBaseId && !$rewardDynaId) return null;

        if ($rewardBaseId) { // base reward
            $link = TournamentToTeam::findOne([
                'tournament_id' => $tournamentId,
                'reward_base' => $rewardBaseId,
            ]);

            if (null !== $link) {
                return Team::findOne($link->team_id);
            }

            return null;
        } else { // dynamic reward
            $link = TournamentToTeam::findOne([
                'tournament_id' => $tournamentId,
                'reward_dyna' => $rewardDynaId,
            ]);

            if (null !== $link) {
                return Team::findOne($link->team_id);
            }

            return null;
        }
    }

    /**
     * @param int|string $tournamentId
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @param int|string $rewardDynaSecId
     * @return int|null winner
     */
    public function getWinnerParticipantTeam($tournamentId, $rewardBaseId = null, $rewardDynaId = null, $rewardDynaSecId = null)
    {
        $rewardBaseId = intval($rewardBaseId);
        $rewardDynaId = intval($rewardDynaId);
        $rewardDynaSecId = intval($rewardDynaSecId);
        if ($rewardBaseId < 1 || $rewardBaseId > 4) {
            $rewardBaseId = 0;
        }

        if (!$rewardBaseId && !$rewardDynaId && !$rewardDynaSecId) return null;

        if ($rewardBaseId) { // base reward
            $link = TournamentToTeam::findOne([
                'tournament_id' => $tournamentId,
                'reward_base' => $rewardBaseId,
            ]);

            if (null !== $link) {
                return $link->id;
            }

            return null;
        } elseif ($rewardDynaId) { // dynamic reward
            $link = TournamentToTeam::findOne([
                'tournament_id' => $tournamentId,
                'reward_dyna' => $rewardDynaId,
            ]);

            if (null !== $link) {
                return $link->id;
            }

            return null;
        } elseif ($rewardDynaSecId) { // dynamic reward
            $link = TournamentToTeam::findOne([
                'tournament_id' => $tournamentId,
                'reward_dyna_sec' => $rewardDynaSecId,
            ]);

            if (null !== $link) {
                return $link->id;
            }

            return null;
        }
        return null;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param array $conditions
     * @param string $order
     * @param array $joins
     * @param string $mode mode: 'select' - select records; 'sql' - get sql
     * @return array|string
     * @throws \Throwable
     */
    public function getChampionsRelations(
        $limit = 7,
        $offset = 0,
        array $conditions = [],
        string $order = '',
        array $joins = [],
        string $mode = 'select'
    )
    {
        $where = '';
        if (count($conditions)) {
            $where = 'where ' . implode(' AND ', $conditions);
        }

        if ($order) {
            $order = 'order by ' . $order;
        } else {
            $order = 'order by winners.sort_time DESC';
        }

        $join = '';
        if ($joins) {
            $join = implode(' ', $joins);
        }

        $winnersRelationsSql = <<<SQL
            select winners.* from (
                select * from (
                    SELECT
                        "player" as rel_type,
                        `ttp`.`id`,
                        `ttp`.`tournament_id`,
                        `ttp`.`team_id`,
                        `ttp`.`player_id`,
                        `t`.`date`,
                        `t`.`time`,
                        UNIX_TIMESTAMP(CONCAT(t.date, " ", t.time)) AS `sort_time`
                    FROM
                        `tournament_to_player` `ttp`
                    INNER JOIN `tournament` `t` ON
                        t.id = ttp.tournament_id
                    WHERE
                        `reward_base` = 1
                ) players
            
                UNION
            
                select * from (
                    SELECT
                        "team" as rel_type,
                        `ttt`.`id`,
                        `ttt`.`tournament_id`,
                        `ttt`.`team_id`,
                        null as player_id,
                        `t`.`date`,
                        `t`.`time`,
                        UNIX_TIMESTAMP(CONCAT(t.date, " ", t.time)) AS `sort_time`
                    FROM
                        `tournament_to_team` `ttt`
                    INNER JOIN `tournament` `t` ON
                        t.id = ttt.tournament_id
                    WHERE
                        `reward_base` = 1
                ) teams
            ) winners
            inner join tournament tour on tour.id = winners.tournament_id
            
            {$join}
             
            {$where} 
            
            group by winners.rel_type, winners.id
            
            {$order}

SQL;

        if ($mode == 'sql') {
            return $winnersRelationsSql;
        }

        $winnersRelations = Yii::$app->db->createCommand(
            $winnersRelationsSql . ' limit :limit offset :offset',
            [
            ':limit' => $limit,
            ':offset' => $offset,
            ]
        );

       return $winnersRelations->queryAll();
    }

    /**
     * @param int $tournamentId
     * @return TournamentToTeam[]|TournamentToPlayer[]
     * @throws \Throwable
     */
    public function getParticipantsForBracket(int $tournamentId, $onlyNames = false)
    {
        $names = [];
        $tournament = Tournament::findOne($tournamentId);
        if (!is_object($tournament))
            throw new \Exception('Tournament not found');

        if ($tournament->type->team_mode) {
            $participants = TournamentToTeam::find()->alias('ttt')->groupBy(['ttt.id'])
                ->select('ttt.*, t.name as team_name')
                ->innerJoin(Team::tableName() . ' t', 't.id = ttt.team_id')
                ->where(['ttt.tournament_id' => $tournament->id])
                ->orderBy('t.name')
                ->asArray()
                ->indexBy('id')
                ->all();

            if ($onlyNames) {
                foreach ($participants as $participant) {
                    $names[$participant['id']] = $participant['team_name'];
                }
            }
        } else {
            $participants = TournamentToPlayer::find()->alias('ttp')->groupBy(['ttp.id'])
                ->select('ttp.*, p.nick as player_nick, c.name as player_class')
                ->innerJoin(Player::tableName() . ' p', 'p.id = ttp.player_id')
                ->leftJoin(PlayerClass::tableName() . ' c', 'c.id = ttp.class_id')
                ->where(['ttp.tournament_id' => $tournament->id])
                ->orderBy('p.nick')
                ->asArray()
                ->indexBy('id')
                ->all();

            if ($onlyNames) {
                foreach ($participants as $participant) {
                    $names[$participant['id']] = $participant['player_nick'];
                }
            }

            $players = Player::find()->indexBy('id')
                ->where(['id' => ArrayHelper::getColumn($participants, 'player_id')])
                ->all();

            foreach ($participants as &$participant) {
                $player = $players[$participant['player_id']] ?? null;
                $participant['player_avatar'] = $player
                    ? $player->getAvatar($participant['tournament_id']) : '';
            }

        }

        return $onlyNames ? $names : $participants;
    }
}