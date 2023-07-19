<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tournament_rule".
 *
 * @property int $id
 * @property int|null $tournament_id
 * @property string $title
 * @property string $description
 * @property int $order
 *
 * @property Tournament $tournament
 */
class TournamentRule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tournament_rule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['tournament_id', 'order'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
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
            'description' => 'Description',
            'order' => 'Order',
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
