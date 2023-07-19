<?php

namespace common\models;

use common\traits\ImageTrait;
use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "media".
 *
 * @property int $id
 * @property string $slug
 * @property string $bg_image
 * @property string $title
 * @property string $content
 * @property string $date
 * @property int $is_text
 * @property int $is_video
 * @property int $active
 *
 * @property Tournament $tournament
 */
class Media extends \yii\db\ActiveRecord
{
    use ImageTrait;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'required' => [['title', 'date'], 'required'],
            [['is_text', 'is_video', 'active'], 'integer'],
            [['title', 'bg_image', 'slug'], 'string', 'max' => 255],
            [['content'], 'string'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'title' => 'Title',
            'content' => 'Content',
            'date' => 'Date',
            'is_text' => 'Contains text',
            'is_video' => 'Contains video',
            'active' => 'Published',
            'bg_image' => 'Background image',
        ];
    }

    public function behaviors()
    {
        return [
            'sluggable' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'ensureUnique' => true,
            ],
        ];
    }

}
