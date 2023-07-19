<?php

namespace common\models\Bracket\Relegation;

use common\models\TournamentToTeam;
use common\models\Team;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property int $id
 * @property int $round_id
 * @property int $level
 * @property int $order
 * @property int $team_one_id
 * @property int $score_one
 * @property int $team_two_id
 * @property int $score_two
 * @property int $winner_id
 * @property int $winner_to_duel_id
 * @property int $loser_id
 * @property int $loser_to_duel_id
 * @property int $completed
 * @property int $active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Round $round
 * @property Team $teamOne
 * @property Team $teamTwo
 * @property TeamDuel $winnerNextDuel
 * @property TeamDuel $loserNextDuel
 */
class TeamDuel extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'bracket_relegation_team_duel';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['round_id', 'level', 'order'], 'integer'],
            [['team_one_id', 'score_one'], 'integer'],
            [['team_two_id', 'score_two'], 'integer'],
            [['winner_id', 'winner_to_duel_id', 'loser_id', 'loser_to_duel_id'], 'integer'],
            [['completed', 'active'], 'boolean'],
            [['score_one', 'score_two'], 'validateScore'],
            [['completed'], 'default', 'value' => false],
            [['active'], 'default', 'value' => true],
        ];
    }

    /**
     * Validate total score
     */
    public function validateScore() {
        $bestOf = $this->round->bracket->best_of;
        if ($this->score_one + $this->score_two > $bestOf) {
            $this->addError('score_one', 'Total score can not be greater than ' . $bestOf);
            $this->addError('score_two', 'Total score can not be greater than ' . $bestOf);
        }
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
     * @return \yii\db\ActiveQuery
     */
    public function getWinnerNextDuel()
    {
        return $this->hasOne(self::class, ['id' => 'winner_to_duel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoserNextDuel()
    {
        return $this->hasOne(self::class, ['id' => 'loser_to_duel_id']);
    }

    /**
     * @return bool
     */
    public function isTeamOneWinner()
    {
        return $this->team_one_id == $this->winner_id;
    }

    /**
     * @return bool
     */
    public function isTeamTwoWinner()
    {
        return $this->team_two_id == $this->winner_id;
    }

    /**
     * @return bool
     */
    public function isTeamOneLoser()
    {
        return $this->team_one_id == $this->loser_id;
    }

    /**
     * @return bool
     */
    public function isTeamTwoLoser()
    {
        return $this->team_two_id == $this->loser_id;
    }


    /**
     * @return bool
     */
    public function hasNextCompleted()
    {
        $bestOf = $this->round->bracket->best_of;

        $wNext = $this->winnerNextDuel;
        if ($wNext) {
            if ((intval($wNext->score_one) + intval($wNext->score_two)) >= intval($bestOf)) {
                return true;
            }
        }

        $lNext = $this->loserNextDuel;
        if ($lNext) {
            if ((intval($lNext->score_one) + intval($lNext->score_two)) >= intval($bestOf)) {
                return true;
            }
        }

        return false;
    }
}
