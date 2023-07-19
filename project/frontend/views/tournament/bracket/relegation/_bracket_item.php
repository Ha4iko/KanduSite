<?php

/** @var $duel \common\models\Bracket\Relegation\Duel */
/** @var $canSetPlayer bool */
/** @var $canSetScore bool */
/** @var $index int */
/** @var $player int */
/** @var $bestOf int */
/** @var $participants array */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Tournament;
use frontend\models\Player;
use common\models\TournamentPrize;
use yii\web\View;
use common\models\PlayerClass;
use common\models\TournamentToPlayer;
use Yii;

$playerIdField = 'player_' . $player;
$playerField = 'player' . $player;
$scoreField = 'score_' . ($player === 1 ? 'one' : 'two');
$score = $duel->$scoreField;

if ($duel->teamMode) {
    $color = 'white';
} else {
    $classId = $player === 1 ? $duel->model->tournamentToPlayerOne->player_id : $duel->model->tournamentToPlayerTwo->player_id;
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

$playerLink = $duel->teamMode ? '' : $duel->$playerField->external_link;
?>

<div class="brackets-item <?= $duel->$playerIdField && $duel->$playerIdField === $duel->winner_id ? 'brackets-item--winner' : '' ?> js-group-result">
    <?php if ($canSetPlayer && !$duel->hasNextCompleted()): ?>
        <div class="brackets-item__select">
            <div class="select select--sm">
                <div class="select-btn">
                    <?= Html::dropDownList('BracketRelegationDuelsForm[' . $index .'][' . $playerIdField . ']', $duel->$playerIdField,
                        $participants,
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
        <?php if ($playerLink && is_string($playerLink)) : ?>
            <a target="_blank" href="<?= $playerLink ?>" class="brackets-item__title no-decor" style="color: <?= $class ?> !important;">
                <?= Html::encode($duel->$playerField->name) ?>
            </a>
        <?php else:  ?>
            <div class="brackets-item__title" style="color: <?= $class ?> !important;">
                <?= Html::encode($duel->$playerField->name) ?>
            </div>
        <?php endif;  ?>
    <?php endif; ?>

    <?php if (!$canSetScore || $duel->hasNextCompleted()): ?>
        <div class="group-item__score"><?= $score ?? '-' ?></div>
    <?php else: ?>
        <?= Html::input('number', 'BracketRelegationDuelsForm[' . $index . '][' . $scoreField . ']', $score, [
            'class' => 'group-item__score js-group-score field',
            'placeholder' => '-',
            'data-best-of' => $bestOf,
            'autocomplete' => 'off'
        ]) ?>
    <?php endif;  ?>
</div>
