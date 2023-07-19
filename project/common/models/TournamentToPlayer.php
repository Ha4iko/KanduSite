<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tournament_player".
 *
 * @property int $id
 * @property int $tournament_id
 * @property int $player_id
 * @property int $class_id
 * @property int $race_id
 * @property int $faction_id
 * @property int $world_id
 * @property int $team_id
 * @property int $reward_base
 * @property int $reward_dyna
 * @property int $reward_dyna_sec
 *
 * @property Player $player
 * @property Tournament $tournament
 * @property PlayerClass $playerClass
 * @property PlayerRace $playerRace
 * @property PlayerFaction $playerFaction
 * @property PlayerWorld $playerWorld
 */
class TournamentToPlayer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tournament_to_player';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tournament_id'], 'required'],
            'player_required' => [['player_id'], 'required'],
            [['tournament_id', 'player_id', 'team_id'], 'integer'],
            [['class_id', 'race_id', 'faction_id', 'world_id'], 'integer'],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => Player::className(), 'targetAttribute' => ['player_id' => 'id']],
            [['tournament_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tournament::className(), 'targetAttribute' => ['tournament_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tournament_id' => 'Tournament ID',
            'player_id' => 'Player ID',
            'class_id' => 'Class',
            'world_id' => 'World',
            'faction_id' => 'Faction',
            'race_id' => 'Race',
            'team_id' => 'Team',
        ];
    }

    /**
     * Gets query for [[Player]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(Player::className(), ['id' => 'player_id']);
    }

    /**
     * Gets query for [[PlayerClass]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerClass()
    {
        return $this->hasOne(PlayerClass::className(), ['id' => 'class_id']);
    }

    /**
     * Gets query for [[PlayerRace]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerRace()
    {
        return $this->hasOne(PlayerRace::className(), ['id' => 'race_id']);
    }

    /**
     * Gets query for [[PlayerFaction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerFaction()
    {
        return $this->hasOne(PlayerFaction::className(), ['id' => 'faction_id']);
    }

    /**
     * Gets query for [[PlayerWorld]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayerWorld()
    {
        return $this->hasOne(PlayerWorld::className(), ['id' => 'world_id']);
    }

    /**
     * Gets query for [[Tournament]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTournament()
    {
        return $this->hasOne(Tournament::className(), ['id' => 'tournament_id']);
    }

}
