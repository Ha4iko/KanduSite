<?php

namespace frontend\models;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * @property string $teamName
 */
class TournamentToTeam extends \common\models\TournamentToTeam
{
    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = ArrayHelper::merge($rules, [
            [['teamName'], 'string', 'max' => 255],
            [['teamName'], 'required'],
        ]);

        return $rules;
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

    /**
     * @param int $teamId
     * @return string|null
     */
    public static function getRelationIdByTeamId(int $teamId)
    {
        $link = static::findOne(['team_id' => $teamId]);
        return is_object($link) ? $link->id : null;
    }
}
