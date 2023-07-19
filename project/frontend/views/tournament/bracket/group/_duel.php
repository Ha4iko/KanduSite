<?php

use yii\web\View;
use yii\helpers\Html;
use common\models\Bracket\Group;
use common\models\Bracket\Group\Duel;
use common\models\Bracket\Group\Round;

/* @var $this View */
/* @var $duel Duel */
/* @var $round Round */
/* @var $index integer */
/* @var $canSetPlayer bool */
/* @var $canSetScore bool */
/* @var $participants array */
/* @var $disableForm bool */
/* @var $bracket Group */

// $availableCellOne = $round->order == 1;
// $availableCellTwo = $round->order == 1;
//
// if ($bracket->participants % 2 && $round->order == 2 && $duel->order == 1) {
//     $availableCellOne = true;
// }
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
                    'player' => 1,
                ])
                : $this->render('_participant', [
                    'duel' => $duel,
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
                    'player' => 2,
                ]) : $this->render('_participant', [
                    'duel' => $duel,
                    'index' => $index,
                    'player' => 2,
                    'canSetPlayer' => $canSetPlayer,
                    'canSetScore' => $canSetScore,
                    'participants' => $participants,
                    'bestOf' => $bracket->best_of
                ])
            ?>
        </div>
    </div>
</div>