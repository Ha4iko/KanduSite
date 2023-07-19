<?php

namespace common\models\Bracket\Relegation;

use common\models\Bracket;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property int $id
 * @property int $bracket_id
 * @property int $level
 * @property int $type_id
 * @property int $loser_from_main
 * @property string $title
 * @property int $active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Bracket $bracket
 * @property bool $isGrand
 * @property bool $isMain
 * @property bool $isDefeat
 */
class Round extends ActiveRecord
{
    const TYPE_MAIN = 1;
    const TYPE_DEFEAT = 2;
    const TYPE_GRAND = 3;

    /**
     * @return array
     */
    public static function getTypeLabels()
    {
        return [
            static::TYPE_MAIN => 'Main bracket',
            static::TYPE_DEFEAT => 'Defeat bracket',
            static::TYPE_GRAND => 'Grand final',
        ];
    }
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'bracket_relegation_round';
    }

    /**
     * @param $bracketId
     * @param $level
     * @param $type
     * @param null $title
     * @return Round
     * @throws \Exception
     */
    static public function findOrCreate($bracketId, $level, $type, $title = null) {
        $round = self::findOne([
            'bracket_id' => $bracketId,
            'level' => $level,
            'type_id' => $type
        ]);
        if (!$round) {
            $round = new self([
                'bracket_id' => $bracketId,
                'level' => $level,
                'type_id' => $type,
                'title' =>  $title ?? ($level . 'th round')
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
     * @return string|null
     */
    public function getTypeLabel()
    {
        $labels = static::getTypeLabels();
        return isset($labels[$this->type_id]) ? $labels[$this->type_id] : null;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['bracket_id', 'type_id', 'loser_from_main', 'active', 'level'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBracket()
    {
        return $this->hasOne(Bracket::class, ['id' => 'bracket_id']);
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
    public function getIsGrand() {
        return $this->type_id === self::TYPE_GRAND;
    }

    /**
     * @return bool
     */
    public function getIsMain() {
        return $this->type_id === self::TYPE_MAIN;
    }

    /**
     * @return bool
     */
    public function getIsDefeat() {
        return $this->type_id === self::TYPE_DEFEAT;
    }
}
