<?php

namespace frontend\models;

use Yii;

/**
 * @property array $bracketParticipants
 */
class BracketTableParticipantsForm extends Bracket
{
    /**
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * @return $this
     */
    public function initDefaultValues()
    {
        return $this;
    }


    /**
     * @return BracketTableColumn[]
     * @throws \yii\db\Exception
     */
    public function getBracketParticipants()
    {
        $participants = TournamentToPlayer::find()->alias('ttp')->groupBy(['ttp.id'])
            ->innerJoin(Player::tableName() . ' p', 'p.id = ttp.player_id')
            ->where(['ttp.tournament_id' => $this->tournament->id])
            ->orderBy('p.nick')
            ->asArray()
            ->indexBy('id')
            ->all();

        if (empty($participants)) return [];

        foreach ($participants as &$participant) {
            $playerAvatar = '';
            $playerClass = '';
            $playerNick = '';
            if ($player = Player::findOne($participant['player_id'])) {
                $playerAvatar = $player->getAvatar($participant['tournament_id']);
                $playerClass = $player->getClassName($participant['tournament_id']);
                $playerNick = $player->nick;
            }

            $participant['player_avatar'] = $playerAvatar;
            $participant['player_class'] = $playerClass;
            $participant['player_nick'] = $playerNick;
        }

        return $participants;
    }

    /**
     * @param $data
     * @param null $formName
     * @return bool
     */
    public function loadForm($data, $formName = null)
    {
        $data = $this->prepareData($data);

        $loaded = parent::load($data, $formName);
        if ($loaded && $this->hasMethod('loadRelations')) {
            $this->loadRelations($data);
        }
        return $loaded;
    }

    /**
     * @param array $data
     * @return array
     */
    public function prepareData(array $data)
    {
        $classNameInData = 'BracketTableRow';
        $dataModels = $data[$classNameInData] ?? [];
        foreach ($dataModels as $k => &$dataModel) {
            if (isset($dataModel['active']) && $dataModel['active'] === 'on') {
                unset($dataModel['active']);
                $dataModel['bracket_id'] = $this->id;
            } else {
                unset($dataModels[$k]);
            }
        }
        $data[$classNameInData] = $dataModels;

        return $data;
    }

    /**
     * @return bool
     */
    public function saveForm()
    {
        if (!$this->validate()) {
            return false;
        }

        return $this->save();
    }

}
