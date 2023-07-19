<?php

namespace frontend\models;

use common\traits\ImageTrait;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * @property string $typeHtml
 * @property string $published
 */
class Media extends \common\models\Media
{
    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = ArrayHelper::merge($rules, [
            //[['published'], 'string'],
            [['published'], 'date', 'format' => 'php:m/d/Y'],
        ]);

        return $rules;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['published'] = 'Published';
        return $labels;
    }


    /**
     * @return array
     */
    public function getRoute()
    {
        return ['/site-media/view', 'slug' => $this->slug];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return Url::to($this->getRoute());
    }

    /**
     * @return string
     */
    public function getTypeHtml()
    {
        $types = [];
        if ($this->is_text) $types[] = 'text';
        if ($this->is_video) $types[] = 'video';
        return implode(' + ', $types);
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getPublished()
    {
        return is_null($this->date) ? '' : Yii::$app->formatter->asDate($this->date, 'php:m/d/Y');
    }

    /**
     * @param string $date
     * @throws \yii\base\InvalidConfigException
     */
    public function setPublished($date)
    {
        $this->date = $date ? Yii::$app->formatter->asDate($date, 'php:Y-m-d') : null;
    }



}
