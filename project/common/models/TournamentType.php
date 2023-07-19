<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tournament_type".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property bool $team_mode
 * @property int $players_in_team
 * @property int $slug
 * @property int $bsg
 *
 * @property Tournament[] $tournaments
 */
class TournamentType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tournament_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'slug'], 'string', 'max' => 255],
            [['team_mode', 'players_in_team', 'bsg'], 'integer'],
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
            'description' => 'Description',
            'team_mode' => 'Mode',
            'slug' => 'Ссылка',
            'players_in_team' => 'Players in team',
            'bsg' => 'BSG',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'sluggable' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'ensureUnique' => true,
            ],
            'typecast' => [
                'class' => AttributeTypecastBehavior::class,
                'attributeTypes' => [
                    'team_mode' => AttributeTypecastBehavior::TYPE_BOOLEAN,
                ],
                'typecastAfterFind' => true
            ]
        ];
    }

    /**
     * Gets query for [[Tournaments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTournaments()
    {
        return $this->hasMany(Tournament::className(), ['type_id' => 'id']);
    }



}
