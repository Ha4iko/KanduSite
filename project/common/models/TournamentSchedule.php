<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tournament_schedule".
 *
 * @property int $id
 * @property int|null $tournament_id
 * @property string $title
 * @property string $date
 * @property string $time
 * @property int $order
 *
 * @property Tournament $tournament
 */
class TournamentSchedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tournament_schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'date', 'time'], 'required'],
            [['tournament_id', 'order'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['time'], 'time', 'format' => 'php:H:i:s'],
            [['tournament_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tournament::className(), 'targetAttribute' => ['tournament_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tournament_id' => 'Tournament ID',
            'title' => 'Title',
            'date' => 'Date',
            'time' => 'Time',
        ];
    }

    /**
     * Gets query for [[Tournament]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTournament()
    {
        return $this->hasOne(Tournament::className(), ['id' => 'tournament_id']);
    }
}
