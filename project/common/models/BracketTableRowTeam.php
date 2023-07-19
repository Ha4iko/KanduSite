<?php

namespace common\models;

use Yii;

/**
 * @property int $id
 * @property int $bracket_id
 * @property int $tournament_to_team_id
 * @property string $created_at
 * @property string $updated_at
 */
class BracketTableRowTeam extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'bracket_table_row_team';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['bracket_id', 'tournament_to_team_id'], 'integer'],
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
}
