<?php

namespace common\models\Bracket\Swiss;

use common\models\Player;
use common\models\Team;
use yii\base\InvalidArgumentException;
use yii\base\Model;

/**
 * @property int $id
 * @property int $round_id
 * @property int $order
 * @property int $player_1
 * @property int $points_one
 * @property int $score_one
 * @property int $player_2
 * @property int $points_two
 * @property int $score_two
 * @property int $winner_id
 * @property int $loser_id
 * @property int $active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Team|Player $player1
 * @property Team|Player $player2
 */
class Duel extends Model
{
    static private $_team_modes = [];

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
        if (!self::$_team_modes[$duelModel->round_id]) {
            self::$_team_modes[$duelModel->round_id] = $duelModel->round->bracket->tournament->type->team_mode;
        }

        return new static([
            'model' => $duelModel,
            'teamMode' => self::$_team_modes[$duelModel->round_id]
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
     * Adds a new error to the specified attribute.
     * @param string $attribute attribute name
     * @param string $error new error message
     */
    public function addError($attribute, $error = '')
    {
        $this->model->addError($attribute, $error);
    }

    /**
     * Returns a value indicating whether there is any validation error.
     * @param string|null $attribute attribute name. Use null to check all attributes.
     * @return bool whether there is any error.
     */
    public function hasErrors($attribute = null)
    {
        return $this->model->hasErrors($attribute);
    }

    /**
     * Removes errors for all attributes or a single attribute.
     * @param string $attribute attribute name. Use null to remove errors for all attributes.
     */
    public function clearErrors($attribute = null)
    {
        $this->model->clearErrors($attribute);
    }

    /**
     * @param bool $runValidation whether to perform validation (calling [[validate()]])
     * @param array $attributeNames list of attribute names that need to be saved. Defaults to null,
     * @return bool whether the saving succeeded (i.e. no validation errors occurred).
     */
    public function save($runValidation = true, $attributeNames = null) {
        return $this->model->save($runValidation, $attributeNames);
    }

    /**
     * @param string[]|string $attributeNames attribute name or list of attribute names that should be validated.
     * @param bool $clearErrors whether to call [[clearErrors()]] before performing validation
     * @return bool whether the validation is successful without any error.
     * @throws InvalidArgumentException if the current scenario is unknown.
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        return $this->model->validate($attributeNames, $clearErrors);
    }

}
