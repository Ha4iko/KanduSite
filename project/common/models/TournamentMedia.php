<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tournament_media".
 *
 * @property int $id
 * @property int|null $tournament_id
 * @property string $content
 * @property int $order
 *
 * @property Tournament $tournament
 * @property array $videoData
 */
class TournamentMedia extends \yii\db\ActiveRecord
{
    const TYPE_UNKNOWN = 'unknown';
    const TYPE_YOUTUBE = 'youtube';
    const TYPE_TWITCH = 'twitch';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tournament_media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tournament_id'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string'],
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
            'content' => 'Youtube video id',
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

    /**
     * @return array
     */
    public function getVideoData()
    {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $this->content, $match)) {
            return [
                'type' => self::TYPE_YOUTUBE,
                'video_id' => $match[1],
            ];
        }
        if (preg_match('%^(?:https?:\/\/)?(?:www.|go.)?twitch.tv(\/videos\/([A-Za-z0-9]+)|\/([A-Za-z0-9]+)\/clip\/([A-Za-z0-9]+)|\/(.*))($|\?)%i', $this->content, $match)) {
            $text = explode('/', $match[1]);
            if ($text[1] == 'videos') {
                return [
                    'type' => self::TYPE_TWITCH,
                    'video_id' => $text[2],
                ];
            } else if ($text[2] == 'clip') {
                return [
                    'type' => self::TYPE_TWITCH,
                    'video_id' => $text[3],
                ];
            } else if (!empty($text[1])){
                return [
                    'type' => self::TYPE_TWITCH,
                    'video_id' => $text[1],
                ];
            }

        }

        return [
            'type' => self::TYPE_UNKNOWN,
            'video_id' => '',
        ];
    }
}
