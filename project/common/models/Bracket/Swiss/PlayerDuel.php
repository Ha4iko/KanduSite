<?php

namespace common\models\Bracket\Swiss;

use common\models\TournamentToPlayer;
use common\models\Player;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property int $id
 * @property int $round_id
 * @property int $order
 * @property int $player_one_id
 * @property int $score_one
 * @property int $points_one
 * @property int $player_two_id
 * @property int $score_two
 * @property int $points_two
 * @property int $winner_id
 * @property int $loser_id
 * @property int $active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Round $round
 * @property Player $playerOne
 * @property Player $playerTwo
 */
class PlayerDuel extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'bracket_swiss_player_duel';
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
            [['round_id', 'order'], 'integer'],
            [['player_one_id', 'player_two_id'], 'integer'],
            [['score_one', 'score_two'], 'integer', 'max' => 5],
            [['scheme_one', 'scheme_two'], 'integer'],
            [['winner_id', 'loser_id'], 'integer'],
            [['active'], 'integer'],
            [['active'], 'default', 'value' => 1],
            [['created_at', 'updated_at'], 'safe'],
            [['score_one', 'score_two'], 'validateScore'],
        ];
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
    public function getPlayerOne()
    {
        return $this->hasOne(Player::class, ['id' => 'player_id'])->viaTable(TournamentToPlayer::tableName(), ['id' => 'player_one_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getPlayerTwo()
    {
        return $this->hasOne(Player::class, ['id' => 'player_id'])->viaTable(TournamentToPlayer::tableName(), ['id' => 'player_two_id']);
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
                $this->winner_id = $this->player_one_id;
                $this->loser_id = $this->player_two_id;
            } elseif ($this->score_one < $this->score_two) {
                $this->winner_id = $this->player_two_id;
                $this->loser_id = $this->player_one_id;
            }
        } else {
            $this->active = 1;
            $this->winner_id = null;
            $this->loser_id = null;
        }

        return true;
    }
}
