<?php

namespace common\models\Bracket\Relegation;

use common\models\Player;
use common\models\Team;
use yii\base\Model;

/**
 * Class Duel
 * @package common\models
 *
 * @property int $id
 * @property int $player_1
 * @property int $player_2
 * @property int $round_id
 * @property int $order
 * @property int $score_one
 * @property int $score_two
 * @property int $winner_id
 * @property int $loser_id
 * @property int $completed
 * @property int $active
 * @property int $winner_to_duel_id
 * @property int $loser_to_duel_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Team|Player $player1
 * @property Team|Player $player2
 * @property Duel $winnerDuel
 * @property Duel $loserDuel
 * @property Round $round
 */
class Duel extends Model
{
    static private $team_modes = [];

    /**
     * @var PlayerDuel|TeamDuel
     */
    public $model;

    /**
     * @var bool
     */
    public $teamMode;

    /**
     * @var array
     */
    private $mapTeams = [
        'player_1' => 'team_one_id',
        'player_2' => 'team_two_id',
        'player1' => 'teamOne',
        'player2' => 'teamTwo',
    ];

    /**
     * @var array
     */
    private $mapPlayers = [
        'player_1' => 'player_one_id',
        'player_2' => 'player_two_id',
        'player1' => 'playerOne',
        'player2' => 'playerTwo',
    ];

    /**
     * @param PlayerDuel|TeamDuel $duelModel
     * @return Duel
     */
    static public function from($duelModel)
    {
        if (!isset(self::$team_modes[$duelModel->round_id])) {
            self::$team_modes[$duelModel->round_id] = $duelModel->round->bracket->tournament->type->team_mode;
        }
        $teamMode = self::$team_modes[$duelModel->round_id];
        return new static([
            'model' => $duelModel,
            'teamMode' => $teamMode
        ]);
    }

    /**
     * @param $duelModels
     * @return array
     */
    static public function fromCollection($duelModels) {
        return array_map(function($model) {
            return self::from($model);
        }, $duelModels);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        if ($this->teamMode) {
            return $this->model[$this->mapTeams[$name] ?? $name] = $value;
        } else {
            return $this->model[$this->mapPlayers[$name] ?? $name] = $value;
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($this->teamMode) {
            return $this->model[$this->mapTeams[$name] ?? $name];
        } else {
            return $this->model[$this->mapPlayers[$name] ?? $name];
        }
    }

    /**
     * @param $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null) {
        return $this->model->load($data, $formName);
    }

    /**
     * @return array
     */
    public function getFirstErrors()
    {
        return $this->model->getFirstErrors();
    }

    /**
     * @return bool
     */
    public function save() {
        return $this->model->save();
    }

    /**
     * @return Duel
     */
    public function getWinnerDuel()
    {
        return $this->model->winnerNextDuel ? self::from($this->model->winnerNextDuel) : null;
    }

    /**
     * @return Duel
     */
    public function getLoserDuel()
    {
        return $this->model->loserNextDuel ? self::from($this->model->loserNextDuel) : null;
    }

    /**
     * @return Duel
     */
    public function getPrevDuel() {

        $model = $this->model::find()
            ->where([
                'OR',
                ['winner_to_duel_id' => $this->id],
                ['loser_to_duel_id' => $this->id]
            ])
            ->one();

        return $model ? Duel::from($model) : null;
    }

    /**
     * @return bool
     */
    public function hasNextCompleted()
    {
        return $this->model->hasNextCompleted();
    }

}