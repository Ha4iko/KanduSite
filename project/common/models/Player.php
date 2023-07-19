<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "player".
 *
 * @property int $id
 * @property string $nick
 * @property string $avatar
 * @property string $external_link
 *
 * @property Player[] $players
 * @property Team[] $teams
 * @property PlayerWorld $world
 * @property string $name
 */
class Player extends \yii\db\ActiveRecord
{
    /**
     * @var int
     */
    public $team_id = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'player';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nick', 'avatar', 'external_link'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nick' => 'Nick',
            'avatar' => 'Avatar',
            'external_link' => 'Profile link',
        ];
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->nick;
    }

}
