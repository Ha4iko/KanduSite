<?php

use frontend\models\BracketGroupDuelsForm;
use yii\web\View;
use yii\helpers\Html;
use common\models\Bracket\Group;
use common\models\Bracket\Group\Round;
use common\models\Bracket\Group\Duel;
use common\models\PlayerClass;
use common\models\TournamentToPlayer;

/* @var $this View */
/* @var $duel Duel */
/* @var $index integer */
/* @var $player integer */
/* @var $canSetPlayer bool */
/* @var $canSetScore bool */
/* @var $participants array */
/* @var $bestOf int */

$playerIdField = 'player_' . $player;
$playerField = 'player' . $player;
$scoreField = 'score_' . ($player === 1 ? 'one' : 'two');
$nameField = $duel->teamMode ? 'name' : 'nick';
$score = $duel->$scoreField;
if ($duel->teamMode) {
    $color = 'white';
} else {
    $classId = $player === 1 ? $duel->model->tournamentToPlayerOne->class_id : $duel->model->tournamentToPlayerTwo->class_id;
    $color = 1;

    $ttp = TournamentToPlayer::findAll([
        'tournament_id' => $duel->model->tournamentToPlayerOne->tournament_id,
        'player_id' => $classId,
    ]);
    $link = $ttp[0] ?? null;
    $link = $ttp[0] ?? null;
    $link = $link ? PlayerClass::findOne($link->class_id) : null;
    $class = $link->avatar;
}
?>
<div class="group-item__result <?= $duel->$playerIdField && $duel->$playerIdField === $duel->winner_id ? 'active' : '' ?> js-group-result">
    <?php if ($canSetPlayer && ($player === 1 ? $duel->isBaseParticipantOne() : $duel->isBaseParticipantTwo()) ): ?>
    <div class="group-item__select">
        <div class="select select--sm">
            <div class="select-btn">
                <?= Html::dropDownList('Duels[' . $index .'][' . $playerIdField . ']', $duel->$playerIdField,
                    array_merge([null => '', $participants]),
                    [
                        'prompt' => 'choose',
                        'class' => 'js-custom-select',
                        'data-placeholder' => 'choose',
                        'data-style' => '2',
                        'data-drop' => 'select--md first-null',
                    ]
                ) ?>
            </div>
            <div class="select-drop">
                <div class="close js-close">
                    <div class="close-inner">
                        <div class="close-icon">
                            <svg class="icon">
                                <use href="images/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>
                        </div>
                        <div class="close-text">close</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
        <div class="brackets-item__title" style="color: <?= $class ?> !important;">
            <?php if (isset($duel->$playerField->external_link) && $duel->$playerField->external_link): ?>
                <a href="<?= $duel->$playerField->external_link ?>" class="no-decor" target="_blank" style="color: <?= $class ?> !important;">
                    <?= Html::encode($duel->$playerField->$nameField) ?>
                </a>
            <?php else: ?>
                <?= Html::encode($duel->$playerField->$nameField) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (!$canSetScore): ?>
        <div class="group-item__score"><?= $score ?? '-' ?></div>
    <?php else: ?>
        <?= Html::input('number', 'Duels[' . $index . '][' . $scoreField . ']', $duel->$scoreField,
            [
                'class' => 'group-item__score js-group-score field' . ($duel->model->hasErrors() ? ' has-error' : ''),
                'placeholder' => '-',
                'data-best-of' => $bestOf,
                'autocomplete' => 'off'

        ]) ?>
    <?php endif; ?>
</div>