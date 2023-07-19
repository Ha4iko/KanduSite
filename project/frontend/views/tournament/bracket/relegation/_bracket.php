<?php

/** @var $this \yii\web\View */
/** @var $bracket \common\models\Bracket\Relegation */
/** @var $rounds \common\models\Bracket\Relegation\Round[] */
/** @var $isDefeat bool */
/** @var $isGrand bool */
/** @var $participants array */
/** @var $readOnly bool */

use yii\helpers\Html;
use common\models\Bracket\Relegation\Duel;
use common\models\Tournament;

$asciiFrom = 64;
$isDefeat && $asciiFrom = 79;
$isGrand && $asciiFrom = 87;
$completedDuelsCount = $bracket->completedDuelsCount;

if ($isGrand && $bracket->second_defeat) {
    /** @var \common\models\Bracket\Relegation\Round $firstRound */
    $firstRound = $rounds[0];
    $firstDuel = $firstRound->getDuels()[0];
    if ($firstDuel && $firstDuel->winner_id && $firstDuel->loser_id) {
        $prevDuel = $firstDuel->getPrevDuel();
        $showSecondRound = $prevDuel->winner_id === $firstDuel->loser_id && $prevDuel->round->isMain;

        if (!$showSecondRound) {
            $rounds = [$rounds[0]];
        }
    } else {
        $rounds = [$rounds[0]];
    }

}

?>

<div class="brackets">
    <div class="brackets-inner">
        <div class="brackets-area">
            <div class="brackets-row">
                <?php foreach ($rounds as $round): ?>
                    <div class="brackets-col">
                        <div class="brackets-title">
                            <div class="group-title prop">
                                <?= Html::encode($round->title) ?>
                            </div>
                        </div>
                        <div class="brackets-round">
                            <?php foreach ($round->duels as $i => $duel) :
                                $duel = Duel::from($duel);
                                $canSetPlayer = !$readOnly && $round->level === 1 && !$isGrand
                                    && $bracket->editable_participants &&
                                    ((intval($duel->score_one) + intval($duel->score_two)) < $bracket->best_of);
                                $canSetScore = !$readOnly && $duel->player_1 && $duel->player_2
                                    && $bracket->editable_scores;
                                if ($bracket->tournament->status == Tournament::STATUS_COMPLETED) {
                                    $canSetPlayer = false;
                                    $canSetScore = false;
                                }

                                $index = $this->params['__index']++;
                                ?>
                                <div class="brackets-game <?= $canSetPlayer ? 'brackets-game--admin' : '' ?> js-group-item">
                                    <div class="brackets-game__title">
                                        <?= chr($asciiFrom + $round->level) ?><?= $i + 1 ?>
                                    </div>
                                    <div class="brackets-game__items">
                                        <?php if ($canSetPlayer || $canSetScore): ?>
                                            <?= Html::hiddenInput('BracketRelegationDuelsForm[' . $index . '][id]', $duel->id) ?>
                                        <?php endif;?>
                                        <?= $this->render('_bracket_item', [
                                            'duel' => $duel,
                                            'player' => 1,
                                            'index' => $index,
                                            'canSetPlayer' => $canSetPlayer && !isset(Yii::$app->params['preview']),
                                            'canSetScore' => $canSetScore && !isset(Yii::$app->params['preview']),
                                            'participants' => $participants,
                                            'bestOf' => $bracket->best_of
                                        ]) ?>
                                        <?= $this->render('_bracket_item', [
                                            'duel' => $duel,
                                            'player' => 2,
                                            'index' => $index,
                                            'canSetPlayer' => $canSetPlayer && !isset(Yii::$app->params['preview']),
                                            'canSetScore' => $canSetScore && !isset(Yii::$app->params['preview']),
                                            'participants' => $participants,
                                            'bestOf' => $bracket->best_of
                                        ]) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="brackets-title">
                            <div class="group-title prop">
                                <?= Html::encode($round->title) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (!$isGrand): ?>
                <div class="brackets-col">
                    <div class="brackets-title">
                        <div class="group-title prop">grand final</div>
                    </div>
                    <div class="brackets-round">
                        <div class="brackets-game">
                            <a class="brackets-game__btn btn btn--sm " href="#" onclick="$('[data-tab=bracket3]').click(); return false;">
                                grand final bracket
                            </a>
                        </div>
                    </div>
                    <div class="brackets-title">
                        <div class="group-title prop">grand final</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
