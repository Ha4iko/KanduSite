<?php

namespace common\models;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property int $id
 * @property int $tournament_id
 * @property int $bracket_type
 * @property string $title
 * @property int $order
 * @property string $created_at
 * @property string $updated_at
 * @property int $participants
 * @property int $best_of
 * @property int $third_place
 * @property int $second_defeat
 * @property int $group_count
 * @property int $round_count
 * @property int $editable
 * @property int $editable_participants
 * @property int $editable_scores
 *
 * @property Tournament $tournament
 * @property BracketTableColumn[] $bracketTableColumns
 * @property BracketTableRow[] $bracketTableRows
 * @property BracketTableRowTeam[] $bracketTableRowsTeam
 */
class Bracket extends ActiveRecord
{
    use SaveRelationsTrait;

    const TYPE_TABLE = 0;
    const TYPE_RELEGATION = 1;
    const TYPE_GROUP = 2;
    const TYPE_SWISS = 3;

    /**
     * @return array
     */
    public static function getTypeLabels()
    {
        return [
            static::TYPE_TABLE => 'Table',
            static::TYPE_RELEGATION => 'Relegation',
            static::TYPE_GROUP => 'Grouped',
            static::TYPE_SWISS => 'Swiss',
        ];
    }

    /**
     * @return mixed|null
     */
    public function getTypeLabel()
    {
        $labels = static::getTypeLabels();
        return isset($labels[$this->bracket_type]) ? $labels[$this->bracket_type] : null;
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'bracket';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'bracket_type'], 'required'],
            [['tournament_id', 'bracket_type', 'order', 'round_count', 'editable'], 'integer'],
            [['participants', 'best_of', 'third_place', 'second_defeat', 'group_count'], 'integer'],
            [['editable_participants', 'editable_scores'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['bracketTableColumns', 'bracketTableRows', 'bracketTableRowsTeam'], 'safe'],
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
            'relations' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'bracketTableColumns',
                    'bracketTableRows',
                    'bracketTableRowsTeam',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tournament_id' => 'Tournament',
            'bracket_type' => 'Type',
            'order' => 'Order',
            'title' => 'Title',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTournament()
    {
        return $this->hasOne(Tournament::class, ['id' => 'tournament_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBracketTableColumns()
    {
        return $this->hasMany(BracketTableColumn::class, ['bracket_id' => 'id'])
            ->orderBy(['order' => SORT_ASC]);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBracketTableRows()
    {
        return $this->hasMany(BracketTableRow::class, ['bracket_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBracketTableRowsTeam()
    {
        return $this->hasMany(BracketTableRowTeam::class, ['bracket_id' => 'id']);
    }

}
