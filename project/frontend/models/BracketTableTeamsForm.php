<?php

namespace frontend\models;

use Yii;

/**
 * @property array $bracketParticipants
 */
class BracketTableTeamsForm extends Bracket
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
        $participants = TournamentToTeam::find()->alias('ttt')->groupBy(['ttt.id'])
            ->innerJoin(Team::tableName() . ' t', 't.id = ttt.team_id')
            ->where(['ttt.tournament_id' => $this->tournament->id])
            ->orderBy('t.name')
            ->asArray()
            ->indexBy('id')
            ->all();

        if (empty($participants)) return [];

        foreach ($participants as &$participant) {
            $teamName = '';
            if ($team = Team::findOne($participant['team_id'])) {
                $teamName = $team->name;
            }

            $participant['team_name'] = $teamName;
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
        $classNameInData = 'BracketTableRowTeam';
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
