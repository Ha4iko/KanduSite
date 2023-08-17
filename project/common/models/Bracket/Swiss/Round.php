<?php

namespace common\models\Bracket\Swiss;

use common\models\Bracket;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property int $id
 * @property int $bracket_id
 * @property string $title
 * @property int $active
 * @property int $order
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Bracket\Swiss $bracket
 * @property Round $prevRound
 * @property boolean $completed
 * @property boolean $filled
 */
class Round extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'bracket_swiss_round';
    }

    /**
     * @param $bracketId
     * @param $order
     * @return Round
     * @throws \Exception
     */
    static public function findOrCreate($bracketId, $order) {
        $round = self::findOne([
            'bracket_id' => $bracketId,
            'order' => $order,
        ]);
        if (!$round) {
            $round = new self([
                'bracket_id' => $bracketId,
                'order' => $order,
                'title' => $order . 'th round'
            ]);
            if (!$round->save()) {
                throw new \Exception('Bracket round was not created: ' . json_encode($round->getFirstErrors(), JSON_UNESCAPED_UNICODE));
            }
        }
        return $round;
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
            [['bracket_id', 'active', 'order'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBracket()
    {
        return $this->hasOne(Bracket\Swiss::class, ['id' => 'bracket_id']);
    }

    /**
     * @return Round|null
     */
    public function getPrevRound() {
        return self::findOne([
            'bracket_id' => $this->bracket_id,
            'order' => $this->order - 1
        ]);
    }

    /**
     * @param bool $noCollections
     * @return Duel[];
     */
    public function getDuels($noCollections = false) {
        $teamsMode = $this->bracket->tournament->type->team_mode;

        if ($teamsMode) {
            $duels = TeamDuel::find()
                ->where(['round_id' => $this->id])
                ->orderBy('order')
                ->all();

        } else {
            $duels = PlayerDuel::find()
                ->where(['round_id' => $this->id])
                ->orderBy('order')
                ->all();
        }

        return $noCollections ? $duels : Duel::fromCollection($duels);
    }

    /**
     * @return bool
     */
    public function getCompleted() {
        $duels = $this->getDuels();
        $count = 0;
        foreach ($duels as $duel) {
            if (!$duel->active) {
                $count++;
            }
        }

        return $count === intval(floor($this->bracket->participants / 2));
    }

    /**
     * @return bool
     */
    public function getFilled() {
        $duels = $this->getDuels();
        $players = 0;
        foreach ($duels as $duel) {
            if ($duel->player_1) {
                $players++;
            }
            if ($duel->player_2) {
                $players++;
            }
        }

        return $players === $this->bracket->participants;
    }
}
