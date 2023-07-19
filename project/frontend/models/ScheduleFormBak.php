<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

/**
 * Schedule form
 *
 * @property integer $tournament_id
 * @property boolean $schedule_type
 * @property boolean $show_on_main_page
 * @property boolean $is_primary
 */
class ScheduleFormBak extends Model
{
    /**
     * @var Tournament|null $tournament
     */
    private $tournament;

    /**
     * @var integer|null
     */
    public $tournament_id;

    /**
     * @var boolean
     */
    public $schedule_type;

    /**
     * @var boolean
     */
    public $show_on_main_page;

    /**
     * @var boolean
     */
    public $is_primary;

    /**
     * @throws HttpException
     */
    public function init()
    {
        parent::init();
        $this->getTournament();
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['tournament_id'], 'integer'],
            [['schedule_type', 'show_on_main_page', 'is_primary'], 'normalizeMark'],
        ];
    }

    /**
     * Normalize attributes with mark-logic
     *
     * @param $attribute
     * @param $params
     * @throws \yii\base\InvalidConfigException
     */
    public function normalizeMark($attribute, $params)
    {
        $post = Yii::$app->request->post();
        $postValue = ArrayHelper::getValue($post, $this->formName() . '.' . $attribute, false);
        $this->$attribute = (strtolower($postValue) == 'on') || boolval($postValue);
    }

    /**
     * Load form data.
     *
     * @return $this
     * @throws HttpException
     */
    public function loadFromTournamentModel()
    {
        $tournament = $this->getTournament();

        $this->schedule_type = $tournament->schedule_type;
        $this->show_on_main_page = $tournament->show_on_main_page;
        $this->is_primary = $tournament->is_primary;

        return $this;
    }

    /**
     * @param string|int|null $tournamentId
     * @param bool $throwIfNotFound
     * @return Tournament|null
     * @throws HttpException
     */
    public function getTournament($tournamentId = null, $throwIfNotFound = true)
    {
        if (!is_object($this->tournament)) {
            if ($this->tournament_id !== null) {
                $this->tournament = Tournament::findOne($this->tournament_id);
            } elseif ($tournamentId !== null) {
                $this->tournament = Tournament::findOne($tournamentId);
            }
        }

        if ($throwIfNotFound && !is_object($this->tournament)) {
            throw new HttpException(500, 'Get tournament fail');
        }

        return $this->tournament;
    }

    /**
     * @return bool
     * @throws HttpException
     * @throws \yii\db\Exception
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $tournament = $this->getTournament();
        $tournament->schedule_type = $this->schedule_type;
        $tournament->show_on_main_page = $this->show_on_main_page;
        $tournament->is_primary = $this->is_primary;
        $tournament->save();

        return true;
    }

    /**
     * @return array|TournamentSchedule[]
     * @throws HttpException
     */
    public function getSchedules()
    {
        return $this->getTournament()->schedules;
    }

    // /**
    //  * Load default values to new model
    //  *
    //  * @return $this the model instance itself.
    //  * @throws \yii\base\InvalidConfigException
    //  */
    // public function initDefaultValues()
    // {
    //     $this->date = Yii::$app->formatter->asDate('now', 'php:Y-m-d');
    //     $this->time = Yii::$app->formatter->asTime('now', 'php:H:00:00');
    //     return $this;
    // }
}
