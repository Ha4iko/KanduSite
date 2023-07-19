<?php

namespace frontend\models;

use common\models\TournamentMedia;
use Yii;
use common\models\TournamentRule;
use common\services\TournamentService;
use yii\base\Exception;
use yii\helpers\Url;
use common\models\TournamentPrize;

/**
 * @property string $typeName
 * @property string $organizerNick
 * @property string $languageName
 * @property string $organizerName
 * @property string $url
 * @property array $route
 * @property TournamentRule[] $tournamentRulesNotEmpty
 * @property TournamentMedia[] $tournamentMediaNotEmpty
 * @property TournamentPrize[] $standardPrizes
 * @property Bracket[] $brackets
 * @property array $participants
 */
class Tournament extends \common\models\Tournament
{
    /**
     * @var $tournamentService TournamentService
     */
    private $tournamentService;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        $this->tournamentService = Yii::$container->get(TournamentService::class);
        parent::init();
    }

    /**
     * @param bool $withEmpty
     * @return array
     */
    public function getTournamentPrizesGrouped(bool $withEmpty = false)
    {
        $tournamentPrizes = [];
        foreach ($this->tournamentPrizes as $prize) {
            if (!$withEmpty && !$prize->money) continue;
            $tournamentPrizes[$prize->type_id][] = $prize;
        }
        return $tournamentPrizes;
    }

    /**
     * @param bool $withEmpty
     * @param int $typeOfPrize example TournamentPrize::TYPE_STANDARD
     * @return TournamentPrize[]
     */
    public function getTournamentPrizesByType(int $typeOfPrize, bool $withEmpty = false)
    {
        $prizesGrouped = $this->getTournamentPrizesGrouped($withEmpty);
        return isset($prizesGrouped[$typeOfPrize]) ? $prizesGrouped[$typeOfPrize] : [];
    }

    /**
     * @param bool $withEmptyPlayers
     * @return array
     */
    public function getParticipantsData(bool $withEmptyPlayers = false)
    {
        return $this->tournamentService->getParticipantsData($this, $withEmptyPlayers);
    }

    /**
     * @param bool $withEmptyPlayers
     * @return array
     */
    public function getParticipantsWithTeams(bool $withEmptyPlayers = false)
    {
        return $this->tournamentService->getParticipantsWithTeams($this, $withEmptyPlayers);
    }

    /**
     * @param bool $withEmpty
     * @return array
     * @throws Exception
     */
    public function getWinnersPlayersStandard(bool $withEmpty = false)
    {
        return $this->tournamentService->getWinnersStandard('player', $this->id, $withEmpty);
    }

    /**
     * @param bool $withEmpty
     * @return array
     * @throws Exception
     */
    public function getWinnersTeamsStandard(bool $withEmpty = false)
    {
        return $this->tournamentService->getWinnersStandard('team', $this->id, $withEmpty);
    }

    /**
     * @param bool $withEmpty
     * @param int $typeOfPrize example TournamentPrize::TYPE_SPECIAL
     * @return array
     * @throws Exception
     */
    public function getWinnersPlayersDynamic(int $typeOfPrize = 0, bool $withEmpty = false)
    {
        return $this->tournamentService->getWinnersDynamic('player', $this->id, $typeOfPrize, $withEmpty);
    }

    /**
     * @param bool $withEmpty
     * @param int $typeOfPrize example TournamentPrize::TYPE_SPECIAL
     * @return array
     * @throws Exception
     */
    public function getWinnersTeamsDynamic(int $typeOfPrize = 0, bool $withEmpty = false)
    {
        return $this->tournamentService->getWinnersDynamic('team', $this->id, $typeOfPrize, $withEmpty);
    }

    /**
     * @param bool $onlyNicks
     * @return Player[]|array
     */
    public function getPlayersWithoutRewards($onlyNicks = false)
    {
        $participants = $this->tournamentService->getPlayersWithoutRewards($this->id);

        if ($onlyNicks) {
            $nicks = [];
            foreach ($participants as $participant) {
                $nicks[$participant->id] = $participant->nick;
            }

            return $nicks;
        }

        return $participants;
    }

    /**
     * @param bool $onlyNames
     * @return Team[]|array
     */
    public function getTeamsWithoutRewards($onlyNames = false)
    {
        $participants = $this->tournamentService->getTeamsWithoutRewards($this->id);

        if ($onlyNames) {
            $names = [];
            foreach ($participants as $participant) {
                $names[$participant->id] = $participant->name;
            }

            return $names;
        }

        return $participants;
    }

    /**
     * @param bool $onlyNicks
     * @return Player[]|array
     */
    public function getParticipantsPlayers($onlyNicks = false)
    {
        $participants = $this->players;

        if ($onlyNicks) {
            $nicks = [];
            foreach ($participants as $participant) {
                $nicks[$participant->id] = $participant->nick;
            }

            return $nicks;
        }

        return $participants;
    }

    /**
     * @param bool $onlyNames
     * @return Team[]|array
     */
    public function getParticipantsTeams($onlyNames = false)
    {
        $participants = $this->teams;

        if ($onlyNames) {
            $names = [];
            foreach ($participants as $participant) {
                $names[$participant->id] = $participant->name;
            }

            return $names;
        }

        return $participants;
    }

    /**
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @return Player|null winner
     */
    public function getWinnerPlayer($rewardBaseId = null, $rewardDynaId = null)
    {
        return $this->tournamentService->getWinnerPlayer($this->id, $rewardBaseId, $rewardDynaId);
    }

    /**
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @param int|string $rewardDynaSecId
     * @return int|null winner
     */
    public function getWinnerParticipantPlayer($rewardBaseId = null, $rewardDynaId = null, $rewardDynaSecId = null)
    {
        return $this->tournamentService->getWinnerParticipantPlayer($this->id, $rewardBaseId, $rewardDynaId, $rewardDynaSecId);
    }

    /**
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @return Team|null winner
     */
    public function getWinnerTeam($rewardBaseId = null, $rewardDynaId = null)
    {
        return $this->tournamentService->getWinnerTeam($this->id, $rewardBaseId, $rewardDynaId);
    }

    /**
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @param int|string $rewardDynaSecId
     * @return int|null winner
     */
    public function getWinnerParticipantTeam($rewardBaseId = null, $rewardDynaId = null, $rewardDynaSecId = null)
    {
        return $this->tournamentService->getWinnerParticipantTeam($this->id, $rewardBaseId, $rewardDynaId, $rewardDynaSecId);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayers()
    {
        return $this->hasMany(Player::class, ['id' => 'player_id'])->via('tournamentToPlayer');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeams()
    {
        return $this->hasMany(Team::class, ['id' => 'team_id'])->via('tournamentToTeam');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTournamentToPlayer()
    {
        return $this->hasMany(TournamentToPlayer::class, ['tournament_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTournamentToTeam()
    {
        return $this->hasMany(TournamentToTeam::class, ['tournament_id' => 'id']);
    }

    /**
     * @param string $nullCaption
     * @return string
     */
    public function getTypeName($nullCaption = '')
    {
        $type = $this->type;
        return is_object($type) ? $type->name : $nullCaption;
    }

    /**
     * @param string $nullCaption
     * @return string
     */
    public function getLanguageName($nullCaption = '')
    {
        $language = $this->language;
        return is_object($language) ? $language->name : $nullCaption;
    }

    /**
     * @param string $nullCaption
     * @return string
     */
    public function getOrganizerName($nullCaption = '')
    {
        $organizer = $this->organizer;
        return is_object($organizer) ? $organizer->username : $nullCaption;
    }

    /**
     * @return TournamentRule[]
     */
    public function getTournamentRulesNotEmpty()
    {
        $tournamentRules = [];
        foreach ($this->tournamentRules as $rule) {
            if (!trim($rule->title) && !trim($rule->description)) continue;
            $tournamentRules[] = $rule;
        }
        return $tournamentRules;
    }

    /**
     * @return TournamentRule[]
     */
    public function getTournamentMediaNotEmpty()
    {
        $tournamentMedia = [];
        foreach ($this->tournamentMedias as $media) {
            if (!trim($media->content)) continue;
            $tournamentMedia[] = $media;
        }
        return $tournamentMedia;
    }

    /**
     * @return array
     */
    public function getRoute()
    {
        return ['/tournament/brackets', 'slug' => $this->slug];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return Url::to($this->getRoute());
    }

    /**
     * Gets query for [[TournamentSchedules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTournamentSchedules()
    {
        return $this->hasMany(TournamentSchedule::class, ['tournament_id' => 'id'])->orderBy('date asc, time asc');
    }

    /**
     * @return string|null
     */
    public function getOrganizerNick()
    {
        $user = User::findOne(['id' => $this->organizer_id]);
        return is_object($user) ? $user->username : null;
    }

    /**
     * @return array
     */
    public function getStandardPrizes()
    {
        $prizes = [];
        for ($i = 1; $i < 5; $i++) {
            if ($i === 1 && ($reward = trim($this->prize_one))) {
                $prizes[$i] = ['reward' => $reward, 'description' => trim($this->getAttributeLabel('prize_one'))];
            }
            if ($i === 2 && ($reward = trim($this->prize_two))) {
                $prizes[$i] = ['reward' => $reward, 'description' => trim($this->getAttributeLabel('prize_two'))];
            }
            if ($i === 3 && ($reward = trim($this->prize_three))) {
                $prizes[$i] = ['reward' => $reward, 'description' => trim($this->getAttributeLabel('prize_three'))];
            }
            if ($i === 4 && ($reward = trim($this->prize_four))) {
                $prizes[$i] = ['reward' => $reward, 'description' => trim($this->getAttributeLabel('prize_four'))];
            }
        }

        return $prizes;
    }

    /**
     * @return array
     */
    public function getParticipantIdsInAllBrackets()
    {
        $ids = [];
        foreach ($this->brackets as $bracket) {
            if ($bracket->bracket_type == Bracket::TYPE_TABLE) {
                $rows = \common\models\BracketTableRow::findAll(['bracket_id' => $bracket->id]);
                foreach ($rows as $row) {
                    if ($row->tournament_to_player_id) $ids[$row->tournament_to_player_id] = $row->tournament_to_player_id;
                }
                $rows = \common\models\BracketTableRowTeam::findAll(['bracket_id' => $bracket->id]);
                foreach ($rows as $row) {
                    if ($row->tournament_to_team_id) $ids[$row->tournament_to_team_id] = $row->tournament_to_team_id;
                }
            } elseif ($bracket->bracket_type == Bracket::TYPE_RELEGATION) {
                $rounds = \common\models\Bracket\Relegation\Round::findAll(['bracket_id' => $bracket->id]);
                foreach ($rounds as $round) {
                    $duels = \common\models\Bracket\Relegation\PlayerDuel::findAll(['round_id' => $round->id]);
                    foreach ($duels as $duel) {
                        if ($duel->player_one_id) $ids[$duel->player_one_id] = $duel->player_one_id;
                        if ($duel->player_two_id) $ids[$duel->player_two_id] = $duel->player_two_id;
                    }
                    $duels = \common\models\Bracket\Relegation\TeamDuel::findAll(['round_id' => $round->id]);
                    foreach ($duels as $duel) {
                        if ($duel->team_one_id) $ids[$duel->team_one_id] = $duel->team_one_id;
                        if ($duel->team_two_id) $ids[$duel->team_two_id] = $duel->team_two_id;
                    }
                }
            } elseif ($bracket->bracket_type == Bracket::TYPE_GROUP) {
                $rounds = \common\models\Bracket\Group\Round::findAll(['bracket_id' => $bracket->id]);
                foreach ($rounds as $round) {
                    $duels = \common\models\Bracket\Group\PlayerDuel::findAll(['round_id' => $round->id]);
                    foreach ($duels as $duel) {
                        if ($duel->player_one_id) $ids[$duel->player_one_id] = $duel->player_one_id;
                        if ($duel->player_two_id) $ids[$duel->player_two_id] = $duel->player_two_id;
                    }
                    $duels = \common\models\Bracket\Group\TeamDuel::findAll(['round_id' => $round->id]);
                    foreach ($duels as $duel) {
                        if ($duel->team_one_id) $ids[$duel->team_one_id] = $duel->team_one_id;
                        if ($duel->team_two_id) $ids[$duel->team_two_id] = $duel->team_two_id;
                    }
                }
            } elseif ($bracket->bracket_type == Bracket::TYPE_SWISS) {
                $rounds = \common\models\Bracket\Swiss\Round::findAll(['bracket_id' => $bracket->id]);
                foreach ($rounds as $round) {
                    $duels = \common\models\Bracket\Swiss\PlayerDuel::findAll(['round_id' => $round->id]);
                    foreach ($duels as $duel) {
                        if ($duel->player_one_id) $ids[$duel->player_one_id] = $duel->player_one_id;
                        if ($duel->player_two_id) $ids[$duel->player_two_id] = $duel->player_two_id;
                    }
                    $duels = \common\models\Bracket\Swiss\TeamDuel::findAll(['round_id' => $round->id]);
                    foreach ($duels as $duel) {
                        if ($duel->team_one_id) $ids[$duel->team_one_id] = $duel->team_one_id;
                        if ($duel->team_two_id) $ids[$duel->team_two_id] = $duel->team_two_id;
                    }
                }
            }
        }
        return $ids;
    }




    /**
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        if (Yii::$app->request->isPost) {
            foreach ($data['TournamentRule'] ?? [] as $key => $rulePost) {
                if (!trim($rulePost['title']) && !trim($rulePost['description'])) {
                    unset($data['TournamentRule'][$key]);
                }
            }
            foreach ($data['TournamentMedia'] ?? [] as $key => $mediaPost) {
                if (!trim($mediaPost['content'])) {
                    unset($data['TournamentMedia'][$key]);
                }
            }
            foreach ($data['TournamentSchedule'] ?? [] as $key => $schedulePost) {
                if (!trim($schedulePost['title']) && !trim($schedulePost['dateFormatted'])
                    && !trim($schedulePost['time'] ?? '')) {
                    unset($data['TournamentSchedule'][$key]);
                }
            }

            $this->clearPlayerDoubles($data);

            foreach ($data['TournamentToPlayer'] ?? [] as $key => $playerPost) {
                if (
                    !trim($playerPost['playerNick']) && !trim($playerPost['class_id'])
                    && !trim($playerPost['race_id'])
                ) {
                    unset($data['TournamentToPlayer'][$key]);
                }
            }

            // foreach ($data['TournamentToTeam'] ?? [] as $key => $teamPost) {
            //     if (!trim($teamPost['teamName'])) {
            //         unset($data['TournamentToTeam'][$key]);
            //     }
            // }
            // foreach ($data['teamNamesByOffset'] ?? [] as $key => $teamsPost) {
            //     if (!trim($teamsPost['name'])) {
            //         unset($data['teamNamesByOffset'][$key]);
            //     }
            // }
        }

        $loaded = parent::load($data, $formName);
        if ($loaded && $this->hasMethod('loadRelations')) {
            $this->loadRelations($data);
        }

        return $loaded;
    }

    /**
     * @param array $data
     * @return bool
     */
    private function clearPlayerDoubles(array &$data)
    {
        $toProcess = $data['TournamentToPlayer'] ?? [];
        if (empty($toProcess)) return false;

        $players = [];
        foreach ($toProcess as $key => $playerPost) {
            if (!isset($players[$playerPost['playerNick']])) {
                $players[$playerPost['playerNick']] = $playerPost;
                continue;
            }

            foreach ($playerPost as $fieldName => $fieldValue) {
                if ($fieldName == 'id' || $fieldName == 'playerNick' || $fieldName == 'tournament_id') continue;

                if (!trim($players[$playerPost['playerNick']][$fieldName]) && trim($fieldValue)) {
                    $players[$playerPost['playerNick']][$fieldName] = trim($fieldValue);
                }
            }
        }

        $data['TournamentToPlayer'] = array_values($players);
    }

}
