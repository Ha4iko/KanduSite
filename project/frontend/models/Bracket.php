<?php

namespace frontend\models;

use common\models\BracketGroupGroup;
use common\models\BracketGroupPlayerDuel;
use common\models\BracketGroupRound;
use common\models\BracketGroupTeamDuel;

/**
 * Class Bracket
 * @package frontend\models
 * @property BracketGroupRound[] $bracketRounds
 * @property BracketGroupPlayerDuel[] $bracketGroupPlayerDuels
 * @property BracketGroupTeamDuel[] $bracketGroupTeamDuels
 * @property BracketGroupGroup[] $bracketGroupGroups
 */
class Bracket extends \common\models\Bracket
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBracketTableRows()
    {
        return $this->hasMany(BracketTableRow::class, ['bracket_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBracketRounds()
    {
        return $this->hasMany(BracketGroupRound::class, ['bracket_id' => 'id'])
            ->orderBy('order');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBracketGroupRounds()
    {
        return $this->hasMany(BracketGroupRound::class, ['bracket_id' => 'id'])
            ->orderBy('order');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBracketGroupGroups()
    {
        return $this->hasMany(BracketGroupGroup::class, ['bracket_id' => 'id'])
            ->orderBy('order');
    }

}
