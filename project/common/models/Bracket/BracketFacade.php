<?php

namespace common\models\Bracket;

use frontend\models\Bracket;

/**
 * Class BracketFacade
 * @package common\models\Bracket
 */
class BracketFacade {

    /**
     * @param $bracketId
     * @return Bracket|Relegation|Swiss|Group|null
     */
    static public function from($bracketId) {
        $bracket = Bracket::findOne($bracketId);
        if (!$bracket) return null;

        if ($bracket->bracket_type === $bracket::TYPE_RELEGATION) {
            return Relegation::findOne($bracketId);
        }
        if ($bracket->bracket_type === $bracket::TYPE_SWISS) {
            return Swiss::findOne($bracketId);
        }
        if ($bracket->bracket_type === $bracket::TYPE_GROUP) {
            return Group::findOne($bracketId);
        }
        return $bracket;
    }

}