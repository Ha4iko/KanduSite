<?php

namespace frontend\models;

use common\models\PlayerWorld;
use yii\helpers\ArrayHelper;

/**
 * @property int $postTeamOffset
 * @property string $className
 * @property string $playerNick
 * @property string $worldName
 * @property string $teamName
 */
class TournamentToPlayer extends \common\models\TournamentToPlayer
{
    /**
     * @var int
     */
    public $postTeamOffset = 0;

    /**
     * @var string
     */
    public $playerNick = '';

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        unset($rules['player_required']);
        $rules = ArrayHelper::merge($rules, [
            [['postTeamOffset'], 'integer'],
            [['playerNick', 'teamName', 'worldName'], 'string', 'max' => 255],
            [['class_id', 'race_id', 'playerNick'], 'required'],
        ]);

        return $rules;
    }

    /**
     * @param string $nullCaption
     * @return string
     */
    public function getClassName($nullCaption = '')
    {
        $class = $this->playerClass;
        return is_object($class) ? $class->name : $nullCaption;
    }

    /**
     * @param string $nullCaption
     * @return string
     */
    public function getRaceName($nullCaption = '')
    {
        $race = $this->playerRace;
        return is_object($race) ? $race->name : $nullCaption;
    }

    /**
     * @param string $nullCaption
     * @return string
     */
    public function getFactionName($nullCaption = '')
    {
        $faction = $this->playerFaction;
        return is_object($faction) ? $faction->name : $nullCaption;
    }

    /**
     * @param string $nullCaption
     * @return string
     */
    public function getWorldName($nullCaption = '')
    {
        $world = $this->playerWorld;
        return is_object($world) ? $world->name : $nullCaption;
    }

    /**
     * @param string $name
     */
    public function setWorldName($name)
    {
        $world = PlayerWorld::findOne(['name' => $name]);
        $this->world_id = is_object($world) ? $world->id : null;
    }

    /**
     * @param string $nick
     */
    public function setPlayerNick($nick)
    {
        $player = Player::findOne(['nick' => $nick]);
        $this->player_id = is_object($player) ? $player->id : null;
    }

    /**
     * @return string|null
     */
    public function getPlayerNick()
    {
        $player = Player::findOne(['id' => $this->player_id]);
        return is_object($player) ? $player->nick : null;
    }

    /**
     * @param string $name
     */
    public function setTeamName($name)
    {
        $team = Team::findOne(['name' => $name]);
        $this->team_id = is_object($team) ? $team->id : null;
    }

    /**
     * @return string|null
     */
    public function getTeamName()
    {
        $team = Team::findOne(['id' => $this->team_id]);
        return is_object($team) ? $team->name : null;
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if (trim($this->playerNick)) {
                $this->setPlayerNick(trim($this->playerNick));
            }

            return true;
        } else {
            return false;
        }
    }

}
