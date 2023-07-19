<?php
namespace frontend\models;

use Yii;
use common\models\Tournament;
use common\services\TournamentService;
use yii\helpers\ArrayHelper;

/**
 * @property string $dateFormatted
 * @property string $timeFormatted
 * @property string $dateFinalFormatted
 * @property string $timeFinalFormatted
 */
class TournamentForm extends Tournament
{

    const SCENARIO_ADMIN = 'admin';
    const SCENARIO_ORGANIZER = 'organizer';

    /**
     * @var $tournamentService TournamentService
     */
    private $tournamentService;

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        $this->tournamentService = Yii::$container->get(TournamentService::class);
        parent::init();
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = ArrayHelper::merge($rules, [
            [['dateFormatted', 'dateFinalFormatted'], 'date', 'format' => 'php:m/d/Y'],
            [['timeFormatted', 'timeFinalFormatted'], 'time', 'format' => 'php:H:i'],
            ['time_zone', 'default', 'value' => 0]
        ]);

        return $rules;
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADMIN] = [
            'organizer_id', 'title', 'type_id',
            'language_id', 'time_zone',
            'dateFormatted', 'time',
            'dateFinalFormatted', 'time_final',
            'bg_image', 'pool_custom',
        ];
        $scenarios[self::SCENARIO_ORGANIZER] = [
            'title', 'type_id',
            'language_id', 'time_zone',
            'dateFormatted', 'time',
            'dateFinalFormatted', 'time_final',
            'bg_image', 'pool_custom',
        ];
        return $scenarios;
    }


    /**
     * Load default values to new model
     *
     * @return $this the model instance itself.
     * @throws \yii\base\InvalidConfigException
     */
    public function initDefaultValues()
    {
        $this->date = Yii::$app->formatter->asDate('now', 'php:Y-m-d');
        $this->time = Yii::$app->formatter->asTime('now', 'php:H:00:00');
        $this->time_zone = 0;
        $this->language_id = 0;
        $this->schedule_type = 0;
        $this->show_on_main_page = 0;
        $this->is_primary = 0;
        return $this;
    }

    /**
     * Save tournament.
     *
     * @return string slug of new tournament
     * @throws \Exception
     */
    public function saveTournament()
    {
        if ($this->isNewRecord) {
            $this->organizer_id = Yii::$app->user->getId() ?: null;
        }
        if (!$this->validate()) {
            return false;
        }

        if ($this->isNewRecord) {
            return $this->tournamentService->createTournament($this);
        } else {
            return $this->tournamentService->updateTournament($this);
        }
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
    public function getDateFinalFormatted() {
        return is_null($this->date_final) ? '' : Yii::$app->formatter->asDate($this->date_final, 'php:m/d/Y');
    }

    /**
     * @param $date
     * @throws \yii\base\InvalidConfigException
     */
    public function setDateFinalFormatted($date) {
        $this->date_final = $date ? Yii::$app->formatter->asDate($date, 'php:Y-m-d') : null;
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

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getTimeFinalFormatted() {
        return is_null($this->time_final) ? '' : Yii::$app->formatter->asTime($this->time_final, 'php:H:i');
    }

    /**
     * @param $time
     * @throws \yii\base\InvalidConfigException
     */
    public function setTimeFinalFormatted($time) {
        $this->time_final = $time ? Yii::$app->formatter->asTime($time, 'php:H:i:00') : null;
    }

}