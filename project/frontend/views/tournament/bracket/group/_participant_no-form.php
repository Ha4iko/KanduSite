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
/* @var $player integer */

$playerIdField = 'player_' . $player;
$playerField = 'player' . $player;
$scoreField = 'score_' . ($player === 1 ? 'one' : 'two');
$nameField = $duel->teamMode ? 'name' : 'nick';

$playerLink = $duel->teamMode ? '' : $duel->$playerField->external_link;
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
?>
<div class="group-item__result <?= $duel->$playerIdField && $duel->$playerIdField === $duel->winner_id ? 'active' : '' ?> js-group-result">
    <?php if ($playerLink && is_string($playerLink)) : ?>
        <a target="_blank" href="<?= $playerLink ?>" class="brackets-item__title no-decor" style="color: <?= $class ?> !important;">
            <?= Html::encode($duel->$playerField->$nameField) ?>
        </a>
    <?php else : ?>
        <div class="brackets-item__title" style="color: <?= $class ?> !important;"><?= Html::encode($duel->$playerField->$nameField) ?></div>
    <?php endif; ?>

    <div class="group-item__score"><?= $duel->$scoreField ?: '-' ?></div>
</div>