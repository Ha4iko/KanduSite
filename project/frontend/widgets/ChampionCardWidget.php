<?php

namespace frontend\widgets;

use common\widgets\BaseWidget;
use frontend\models\Player;
use frontend\models\Team;
use frontend\models\Tournament;
use frontend\models\TournamentToPlayer;
use yii\web\HttpException;

class ChampionCardWidget extends BaseWidget
{
    /**
     * @var array
     */
    public $championsRelation;

    /**
     * Init widget
     * @throws HttpException
     */
    public function init()
    {
       parent::init();

        if (!$this->championsRelation) {
            throw new HttpException(500);
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        $tournament = Tournament::findOne($this->championsRelation['tournament_id']);
        if (!is_object($tournament)) return '';

        $player = null;
        $team = null;

        if ($this->championsRelation['rel_type'] == 'player') {
            $team = Team::findOne($this->championsRelation['team_id']);
            $player = Player::findOne($this->championsRelation['player_id']);

            if (!is_object($player)) return '';
        } else {
            $team = Team::findOne($this->championsRelation['team_id']);

            if (!is_object($team)) return '';
        }

        return $this->render($this->championsRelation['rel_type'], [
            'relation' => $this->championsRelation,
            'player' => $player,
            'team' => $team,
            'tournament' => $tournament,
        ]);
    }
}

