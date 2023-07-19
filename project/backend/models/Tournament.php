<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * @property string $statusLabel
 */
class Tournament extends \common\models\Tournament
{
    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = ArrayHelper::merge($rules, [
            ['dateFormatted', 'date', 'format' => 'php:m/d/Y'],
            // ['dateFinalFormatted', 'date', 'format' => 'php:m/d/Y'],
            ['timeFormatted', 'time', 'format' => 'php:H:i'],
            // ['timeFinalFormatted', 'time', 'format' => 'php:H:i'],
        ]);

        return $rules;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getDateFormatted() {
        return is_null($this->date) ? '' : Yii::$app->formatter->asDate($this->date, 'php:m/d/Y');
    }

    /**
     * @param string $date
     * @throws \yii\base\InvalidConfigException
     */
    public function setDateFormatted($date) {
        $this->date = $date ? Yii::$app->formatter->asDate($date, 'php:Y-m-d') : null;
    }


    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getTimeFormatted() {
        return is_null($this->time) ? '' : Yii::$app->formatter->asTime($this->time, 'php:H:i');
    }

    /**
     * @param $time
     * @throws \yii\base\InvalidConfigException
     */
    public function setTimeFormatted($time) {
        $this->time = $time ? Yii::$app->formatter->asTime($time, 'php:H:i:00') : null;
    }
}
