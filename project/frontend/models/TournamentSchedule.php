<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * @property string $dateFormatted
 */
class TournamentSchedule extends \common\models\TournamentSchedule
{
    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = ArrayHelper::merge($rules, [
            [['dateFormatted'], 'required'],
            [['dateFormatted'], 'date', 'format' => 'php:m/d/Y'],
        ]);

        return $rules;
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
            'dateFormatted' => 'Date',
        ];
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


}
