<?php

namespace common\models;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "team".
 *
 * @property int $id
 * @property string $name
 *
 * @property Player[] $players
 */
class Team extends \yii\db\ActiveRecord
{
    use SaveRelationsTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'team';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['players'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'players' => 'Игроки',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'players',
                ],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayers()
    {
        return $this->hasMany(Player::class, ['id' => 'player_id'])->via('teamToPlayer');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamToPlayer()
    {
        return $this->hasMany(TeamToPlayer::class, ['team_id' => 'id']);
    }


}
