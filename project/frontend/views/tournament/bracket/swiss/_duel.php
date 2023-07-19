<?php

use frontend\models\BracketSwissDuelsForm;
use yii\web\View;
use yii\helpers\Html;
use common\models\Bracket\Swiss\Duel;
use common\models\Bracket\Swiss\Round;

/* @var $this View */
/* @var $duel Duel */
/* @var $round Round */
/* @var $index integer */
/* @var $canSetPlayer bool */
/* @var $canSetScore bool */
/* @var $participants array */
/* @var $disableForm bool */
/* @var $classIds array */
/* @var $bracket \common\models\Bracket\Swiss */

if ($bracket->participants % 2 !== 0 && $index === intval(floor($bracket->participants / 2))) {
    $canSetPlayer2 = false;
} else {
    $canSetPlayer2 = true;
}

?>
<div class="group-item js-group-item">
    <?= Html::hiddenInput('Duels[' . $index . '][id]', $duel->id) ?>
    <div class="group-item__inner">
        <div class="group-item__num">
            <?= $duel->order ?>
        </div>
        <div class="group-item__results">
            <?= $disableForm
                ? $this->render('_participant_no-form', [
                    'duel' => $duel,
                    'classIds' => $classIds,
                    'player' => 1,
                ])
                : $this->render('_participant', [
                    'duel' => $duel,
                    'classIds' => $classIds,
                    'index' => $index,
                    'player' => 1,
                    'canSetPlayer' => $canSetPlayer,
                    'canSetScore' => $canSetScore,
                    'participants' => $participants,
                    'bestOf' => $bracket->best_of
                ])
            ?>
            <?= $disableForm
                ? $this->render('_participant_no-form', [
                    'duel' => $duel,
                    'classIds' => $classIds,
                    'player' => 2,
                ]) : $this->render('_participant', [
                    'duel' => $duel,
                    'classIds' => $classIds,
                    'index' => $index,
                    'player' => 2,
                    'canSetPlayer' => $canSetPlayer && $canSetPlayer2,
                    'canSetScore' => $canSetScore,
                    'participants' => $participants,
                    'bestOf' => $bracket->best_of
                ])
            ?>
        </div>
    </div>
</div>