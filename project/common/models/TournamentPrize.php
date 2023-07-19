<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tournament_prize".
 *
 * @property int $id
 * @property int $tournament_id
 * @property int|null $type_id
 * @property int $money
 * @property string $description
 *
 * @property Tournament $tournament
 */
class TournamentPrize extends \yii\db\ActiveRecord
{
    //const TYPE_STANDARD = 0;
    const TYPE_SPECIAL = 1;
    const TYPE_SECONDARY = 2;

    /**
     * @return array
     */
    public static function getTypeLabels()
    {
        return [
            //static::TYPE_STANDARD => 'Standard prizes',
            static::TYPE_SPECIAL => 'Special prizes',
            static::TYPE_SECONDARY => 'Secondary prizes',
        ];
    }

    /**
     * @return mixed|null
     */
    public function getTypeLabel()
    {
        $labels = static::getTypeLabels();
        return isset($labels[$this->type_id]) ? $labels[$this->type_id] : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tournament_prize';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tournament_id', 'money', 'description'], 'required'],
            [['tournament_id', 'type_id'], 'integer'],
            [['money'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
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
            'type_id' => 'Type ID',
            'money' => 'Prize',
            'description' => 'Name',
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
