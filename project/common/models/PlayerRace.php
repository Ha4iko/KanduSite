<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "player_race".
 *
 * @property int $id
 * @property string $name
 * @property string $avatar
 * @property int $gender
 *
 * @property Player[] $players
 * @property Player[] $genderLabel
 */
class PlayerRace extends \yii\db\ActiveRecord
{
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    /**
     * @return array
     */
    public static function getGenderLabels()
    {
        return [
            static::GENDER_MALE => 'Male',
            static::GENDER_FEMALE => 'Female',
        ];
    }

    /**
     * @return array
     */
    public function getGenderLabel()
    {
        $genders = static::getGenderLabels();
        return isset($genders[$this->gender]) ? $genders[$this->gender] : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'player_race';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender'], 'in', 'range' => [self::GENDER_MALE, self::GENDER_FEMALE]],
            [['name', 'avatar'], 'string', 'max' => 255],
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
            'avatar' => 'Avatar',
            'gender' => 'Gender',
        ];
    }

    /**
     * Gets query for [[Players]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayers()
    {
        return $this->hasMany(Player::className(), ['race_id' => 'id']);
    }


}
