<?php

namespace common\models\Bracket\Group;

use Yii;
use yii\db\ActiveRecord;

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
 * @property PlayerDuel[] $bracketGroupPlayerDuels
 * @property TeamDuel[] $bracketGroupTeamDuels
 */
class Group extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'bracket_group_group';
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
     * @return \yii\db\ActiveQuery
     */
    public function getBracketGroupPlayerDuels()
    {
        return $this->hasMany(BracketGroupPlayerDuel::class, ['group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBracketGroupTeamDuels()
    {
        return $this->hasMany(BracketGroupTeamDuel::class, ['group_id' => 'id']);
    }

}
