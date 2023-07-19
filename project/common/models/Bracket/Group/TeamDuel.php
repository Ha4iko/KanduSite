<?php

namespace common\models\Bracket\Group;

use common\models\Team;
use common\models\TournamentToTeam;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $round_id
 * @property int $group_id
 * @property int $order
 * @property int $team_one_id
 * @property int $scheme_one
 * @property int $score_one
 * @property int $team_two_id
 * @property int $scheme_two
 * @property int $score_two
 * @property int $winner_id
 * @property int $loser_id
 * @property int $active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Round $round
 * @property Group $group
 */
class TeamDuel extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'bracket_group_team_duel';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['round_id', 'group_id', 'order'], 'integer'],
            [['team_one_id', 'team_two_id'], 'integer'],
            [['score_one', 'score_two'], 'integer', 'max' => 5],
            [['scheme_one', 'scheme_two'], 'integer'],
            [['winner_id', 'loser_id'], 'integer'],
            [['active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['score_one', 'score_two'], 'validateScore'],
        ];
    }

    /**
     * Validate scores
     */
    public function validateScore() {
        $bestOf = $this->round->bracket->best_of;
        if ($this->score_one + $this->score_two > $bestOf) {
            $this->addError('score_one', 'Total score can not be greater than ' . $bestOf);
            $this->addError('score_two', 'Total score can not be greater than ' . $bestOf);
        }
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'score_one' => 'Score',
            'score_two' => 'Score',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRound()
    {
        return $this->hasOne(Round::class, ['id' => 'round_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getTeamOne()
    {
        return $this->hasOne(Team::class, ['id' => 'team_id'])->viaTable(TournamentToTeam::tableName(), ['id' => 'team_one_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getTeamTwo()
    {
        return $this->hasOne(Team::class, ['id' => 'team_id'])->viaTable(TournamentToTeam::tableName(), ['id' => 'team_two_id']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (($this->score_one + $this->score_two) >= $this->round->bracket->best_of) {
            $this->active = 0;

            if ($this->score_one > $this->score_two) {
                $this->winner_id = $this->team_one_id;
                $this->loser_id = $this->team_two_id;
            } elseif ($this->score_one < $this->score_two) {
                $this->winner_id = $this->team_two_id;
                $this->loser_id = $this->team_one_id;
            }
        } else {
            $this->active = 1;
            $this->winner_id = null;
            $this->loser_id = null;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isBaseParticipantOne()
    {
        $round = $this->round;
        if ($round->order == 1) return true;
        if ($round->order > 2) return false;

        $bracket = $round->bracket;
        if ($bracket->participants % 2 && $this->order == 1) return true;
        return false;
    }

    /**
     * @return bool
     */
    public function isBaseParticipantTwo()
    {
        $round = $this->round;
        if ($round->order == 1) return true;
        return false;
    }
}
