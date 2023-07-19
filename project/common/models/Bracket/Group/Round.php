<?php

namespace common\models\Bracket\Group;

use common\models\Bracket;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property int $id
 * @property int $bracket_id
 * @property int $order
 * @property string $title
 * @property int $active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Bracket $bracket
 * @property Duel[] $duels
 */
class Round extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'bracket_group_round';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['bracket_id', 'active', 'order'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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

}
