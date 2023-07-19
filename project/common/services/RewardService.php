<?php

namespace common\services;

use common\models\TournamentToTeam;
use common\models\TournamentToPlayer;
use yii\base\Exception;

class RewardService
{
    /**
     * @param int|string $tournamentId
     * @param int|string $playerId
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @param int|string $rewardDynaSecId
     * @return bool operation was ended successfully
     * @throws Exception
     */
    public function addRewardForPlayer($tournamentId, $playerId, $rewardBaseId = null, $rewardDynaId = null, $rewardDynaSecId = null)
    {
        return $this->addReward('player', $tournamentId, $playerId, $rewardBaseId, $rewardDynaId, $rewardDynaSecId);
    }

    /**
     * @param int|string $tournamentId
     * @param int|string $teamId
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @param int|string $rewardDynaSecId
     * @return bool operation was ended successfully
     * @throws Exception
     */
    public function addRewardForTeam($tournamentId, $teamId, $rewardBaseId = null, $rewardDynaId = null, $rewardDynaSecId = null)
    {
        return $this->addReward('team', $tournamentId, $teamId, $rewardBaseId, $rewardDynaId, $rewardDynaSecId);
    }

    /**
     * @param string $target target for reward: player, team
     * @param int|string $tournamentId
     * @param int|string $ownerId
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @param int|string $rewardDynaSecId
     * @return bool operation was ended successfully
     * @throws Exception
     */
    public function addReward(string $target, $tournamentId, $ownerId, $rewardBaseId = null, $rewardDynaId = null, $rewardDynaSecId = null)
    {
        if (!in_array($target, ['player', 'team'])) {
            throw new Exception('Wrong target for addReward()');
        }

        $rewardBaseId = intval($rewardBaseId);
        $rewardDynaId = intval($rewardDynaId);
        $rewardDynaSecId = intval($rewardDynaSecId);
        if ($rewardBaseId < 1 || $rewardBaseId > 4) {
            $rewardBaseId = 0;
        }

        if (!$rewardBaseId && !$rewardDynaId && !$rewardDynaSecId) return false;

        //$rewardRemoved = $this->removeReward($target, $tournamentId, $rewardBaseId, $rewardDynaId);
        //if (!$rewardRemoved) return false;

        if ($target === 'player') {
            $targetClass = TournamentToPlayer::class;
            //$targetAttributeLinkToReward = 'player_id';
            $targetAttributeLinkToReward = 'id';
        } else {
            $targetClass = TournamentToTeam::class;
            //$targetAttributeLinkToReward = 'team_id';
            $targetAttributeLinkToReward = 'id';
        }

        if ($rewardBaseId) {
            $updateAttributes = [
                'reward_base' => $rewardBaseId,
                //'reward_dyna' => null,
            ];
        } elseif ($rewardDynaId) {
            $updateAttributes = [
                //'reward_base' => null,
                'reward_dyna' => $rewardDynaId,
            ];
        } elseif ($rewardDynaSecId) {
            $updateAttributes = [
                //'reward_base' => null,
                'reward_dyna_sec' => $rewardDynaSecId,
            ];
        }
        // var_dump($updateAttributes, [
        //     'tournament_id' => $tournamentId,
        //     $targetAttributeLinkToReward => $ownerId,
        // ]);
        $targetClass::updateAll($updateAttributes, [
            'tournament_id' => $tournamentId,
            $targetAttributeLinkToReward => $ownerId,
        ]);

        return true;
    }

    /**
     * @param int|string $tournamentId
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @param int|string $rewardDynaSecId
     * @return bool operation was ended successfully
     * @throws Exception
     */
    public function removeRewardForPlayers($tournamentId, $rewardBaseId = null, $rewardDynaId = null, $rewardDynaSecId = null)
    {
        return $this->removeRewards('player', $tournamentId, $rewardBaseId, $rewardDynaId, $rewardDynaSecId);
    }

    /**
     * @param int|string $tournamentId
     * @param int|string $playerId
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @param int|string $rewardDynaSecId
     * @return bool operation was ended successfully
     * @throws Exception
     */
    public function removeRewardForPlayer($tournamentId, $playerId, $rewardBaseId = null, $rewardDynaId = null, $rewardDynaSecId = null)
    {
        return $this->removeReward('player', $tournamentId, $playerId, $rewardBaseId, $rewardDynaId, $rewardDynaSecId);
    }

    /**
     * @param int|string $tournamentId
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @param int|string $rewardDynaSecId
     * @return bool operation was ended successfully
     * @throws Exception
     */
    public function removeRewardForTeams($tournamentId, $rewardBaseId = null, $rewardDynaId = null, $rewardDynaSecId = null)
    {
        return $this->removeRewards('team', $tournamentId, $rewardBaseId, $rewardDynaId, $rewardDynaSecId);
    }

    /**
     * @param int|string $tournamentId
     * @param int|string $teamId
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @param int|string $rewardDynaSecId
     * @return bool operation was ended successfully
     * @throws Exception
     */
    public function removeRewardForTeam($tournamentId, $teamId, $rewardBaseId = null, $rewardDynaId = null, $rewardDynaSecId = null)
    {
        return $this->removeReward('team', $tournamentId, $teamId, $rewardBaseId, $rewardDynaId, $rewardDynaSecId);
    }

    /**
     * @param string $target target for reward: player, team
     * @param int|string $tournamentId
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @param int|string $rewardDynaSecId
     * @return bool operation was ended successfully
     * @throws Exception
     */
    public function removeRewards(string $target, $tournamentId, $rewardBaseId = null, $rewardDynaId = null, $rewardDynaSecId = null)
    {
        if (!in_array($target, ['player', 'team'])) {
            throw new Exception('Wrong target for removeReward()');
        }

        $rewardBaseId = intval($rewardBaseId);
        $rewardDynaId = intval($rewardDynaId);
        $rewardDynaSecId = intval($rewardDynaSecId);
        if ($rewardBaseId < 1 || $rewardBaseId > 4) {
            $rewardBaseId = 0;
        }

        if (!$rewardBaseId && !$rewardDynaId && !$rewardDynaSecId) return false;

        if ($target === 'player') {
            $targetClass = TournamentToPlayer::class;
        } else {
            $targetClass = TournamentToTeam::class;
        }

        if ($rewardBaseId) {
            $updateAttributes = [
                'reward_base' => null
            ];
            $updateCondition = [
                'reward_base' => $rewardBaseId,
            ];
        } elseif ($rewardDynaId) {
            $updateAttributes = [
                'reward_dyna' => null
            ];
            $updateCondition = [
                'reward_dyna' => $rewardDynaId,
            ];
        } elseif ($rewardDynaSecId) {
            $updateAttributes = [
                'reward_dyna_sec' => null
            ];
            $updateCondition = [
                'reward_dyna_sec' => $rewardDynaSecId,
            ];
        }

        $updateCondition['tournament_id'] = $tournamentId;
        //var_dump($updateAttributes, $updateCondition);
        $targetClass::updateAll($updateAttributes, $updateCondition);

        return true;
    }

    /**
     * @param string $target target for reward: player, team
     * @param int|string $tournamentId
     * @param int|string $ownerId
     * @param int|string $rewardBaseId
     * @param int|string $rewardDynaId
     * @param int|string $rewardDynaSecId
     * @return bool operation was ended successfully
     * @throws Exception
     */
    public function removeReward(string $target, $tournamentId, $ownerId, $rewardBaseId = null, $rewardDynaId = null, $rewardDynaSecId = null)
    {
        if (!in_array($target, ['player', 'team'])) {
            throw new Exception('Wrong target for removeReward()');
        }

        $rewardBaseId = intval($rewardBaseId);
        $rewardDynaId = intval($rewardDynaId);
        $rewardDynaSecId = intval($rewardDynaSecId);
        if ($rewardBaseId < 1 || $rewardBaseId > 4) {
            $rewardBaseId = 0;
        }

        if (!$rewardBaseId && !$rewardDynaId && !$rewardDynaSecId) return false;

        if ($target === 'player') {
            $targetClass = TournamentToPlayer::class;
            //$targetAttributeLinkToReward = 'player_id';
            $targetAttributeLinkToReward = 'id';
        } else {
            $targetClass = TournamentToTeam::class;
            //$targetAttributeLinkToReward = 'team_id';
            $targetAttributeLinkToReward = 'id';
        }

        if ($rewardBaseId) {
            $updateAttributes = [
                'reward_base' => null
            ];
            $updateCondition = [
                'reward_base' => $rewardBaseId,
                $targetAttributeLinkToReward => $ownerId,
            ];
        } elseif ($rewardDynaId) {
            $updateAttributes = [
                'reward_dyna' => null
            ];
            $updateCondition = [
                'reward_dyna' => $rewardDynaId,
                $targetAttributeLinkToReward => $ownerId,
            ];
        } elseif ($rewardDynaSecId) {
            $updateAttributes = [
                'reward_dyna_sec' => null
            ];
            $updateCondition = [
                'reward_dyna_sec' => $rewardDynaSecId,
                $targetAttributeLinkToReward => $ownerId,
            ];
        }

        $updateCondition['tournament_id'] = $tournamentId;

        $targetClass::updateAll($updateAttributes, $updateCondition);

        return true;
    }

    /**
     * @param int|string $tournamentId
     * @param string $rewardType type of rewards: all, base, dyna, dyna_sec
     * @return bool operation was ended successfully
     * @throws Exception
     */
    public function clearRewardsForPlayers($tournamentId, string $rewardType)
    {
        return $this->clearRewards('player', $tournamentId, $rewardType);
    }

    /**
     * @param int|string $tournamentId
     * @param string $rewardType type of rewards: all, base, dyna, dyna_sec
     * @return bool operation was ended successfully
     * @throws Exception
     */
    public function clearRewardsForTeams($tournamentId, string $rewardType)
    {
        return $this->clearRewards('team', $tournamentId, $rewardType);
    }

    /**
     * @param string $target target for reward: player, team
     * @param int|string $tournamentId
     * @param string $rewardType type of rewards: all, base, dyna, dyna_sec
     * @return bool operation was ended successfully
     * @throws Exception
     */
    public function clearRewards(string $target, $tournamentId, string $rewardType)
    {
        if (!in_array($target, ['player', 'team'])) {
            throw new Exception('Wrong target for clearRewards()');
        }

        if (!in_array($rewardType, ['all', 'base', 'dyna'])) {
            throw new Exception('Wrong rewardType for clearRewards()');
        }

        if ($target === 'player') {
            $targetClass = TournamentToPlayer::class;
        } else {
            $targetClass = TournamentToTeam::class;
        }

        if ($rewardType === 'base') {
            $updateFields = [
                'reward_base' => null,
            ];
        } elseif ($rewardType === 'dyna') {
            $updateFields = [
                'reward_dyna' => null,
            ];
        } elseif ($rewardType === 'dyna_sec') {
            $updateFields = [
                'reward_dyna_sec' => null,
            ];
        } else { // all
            $updateFields = [
                'reward_base' => null,
                'reward_dyna' => null,
                'reward_dyna_sec' => null,
            ];
        }

        $targetClass::updateAll($updateFields, [
            'tournament_id' => $tournamentId,
        ]);

        return true;
    }

}