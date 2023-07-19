<?php

namespace frontend\models;

use common\models\PlayerClass;
use common\models\PlayerFaction;
use common\models\PlayerRace;
use Yii;

/**
 * @property string $className
 * @property int $avatarId
 */
class Player extends \common\models\Player
{
    /**
     * @param int $tournamentId
     * @return PlayerClass
     */
    public function getParticipantClass($tournamentId)
    {
        $ttp = TournamentToPlayer::findAll([
            'tournament_id' => $tournamentId,
            'player_id' => $this->id,
        ]);
        $link = $ttp[0] ?? null;
        return $link ? PlayerClass::findOne($link->class_id) : null;
    }

    /**
     * @param int $tournamentId
     * @return PlayerRace
     */
    public function getParticipantRace($tournamentId)
    {
        $ttp = TournamentToPlayer::findAll([
            'tournament_id' => $tournamentId,
            'player_id' => $this->id,
        ]);
        $link = $ttp[0] ?? null;
        return $link ? PlayerRace::findOne($link->race_id) : null;
    }

    /**
     * @param int $tournamentId
     * @return PlayerFaction
     */
    public function getParticipantFaction($tournamentId)
    {
        $ttp = TournamentToPlayer::findAll([
            'tournament_id' => $tournamentId,
            'player_id' => $this->id,
        ]);
        $link = $ttp[0] ?? null;
        return $link ? PlayerFaction::findOne($link->faction_id) : null;
    }

    /**
     * @param int $tournamentId
     * @param string $nullCaption
     * @return string
     */
    public function getClassName($tournamentId, $nullCaption = '')
    {
        $class = $this->getParticipantClass($tournamentId);
        return $class ? $class->name : $nullCaption;
    }

    /**
     * @param int $tournamentId
     * @return string
     */
    public function getClassColor($tournamentId)
    {
        $class = $this->getParticipantClass($tournamentId);
        /**
        *return Yii::$app->params['classColors'][$class->id] ?? 'white';
        */
        return $class->avatar;
    }

    /**
     * @param int $tournamentId
     * @param string $nullCaption
     * @return string
     */
    public function getRaceName($tournamentId, $nullCaption = '')
    {
        $race = $this->getParticipantRace($tournamentId);
        return $race ? $race->name : $nullCaption;
    }

    /**
     * @param int $tournamentId
     * @param string $nullCaption
     * @return string
     */
    public function getFactionName($tournamentId, $nullCaption = '')
    {
        $faction = $this->getParticipantFaction($tournamentId);
        return $faction ? $faction->name : $nullCaption;
    }

    /**
     * @param int $tournamentId
     * @return string
     */
    public function getFactionAvatar($tournamentId)
    {
        $faction = $this->getParticipantFaction($tournamentId);

        return $faction->avatar ? IMG_ROOT . '/' . trim($faction->avatar) : null;
    }

    /**
     * @param int $tournamentId
     * @return string
     */
    public function getRaceAvatar($tournamentId)
    {
        $race = $this->getParticipantRace($tournamentId);

        return $race ? $race->avatar : null;
    }

    /**
     * @param int $tournamentId
     * @return string
     */
    public function getAvatar($tournamentId) {
        if ($this->avatar && file_exists(Yii::getAlias('@app/web') . $this->avatar))
            return $this->avatar;

        $raceAvatar = $this->getRaceAvatar($tournamentId);

        if ($raceAvatar && file_exists(Yii::getAlias('@app/web') . $raceAvatar))
            return $raceAvatar;

        return IMG_ROOT . '/champ-avatar1.jpg';
    }

    /**
     * generate and load avatar path
     */
    public function loadAvatar() {
        if ($this->nick) {
            $mask = Yii::getAlias('@app/web/storage/images/user/') . md5($this->nick) . ".*";

            $avatarFile = '';
            foreach (glob($mask) as $avatarFile) {
                if ($avatarFile) {
                    $avatarFile = str_replace(Yii::getAlias('@app/web'), '', $avatarFile);
                    break;
                }
            }
            if ($avatarFile) {
                $this->avatar = $avatarFile;
            }
        }
    }

}
