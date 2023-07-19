<?php

use frontend\models\BracketSwissDuelsForm;
use yii\web\View;
use yii\helpers\Html;
use common\models\Bracket\Swiss;
use common\models\Bracket\Swiss\Round;
use common\models\Bracket\Swiss\Duel;
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
/* @var $classIds array */

$playerIdField = 'player_' . $player;
$playerField = 'player' . $player;
$scoreField = 'score_' . ($player === 1 ? 'one' : 'two');
$nameField = $duel->teamMode ? 'name' : 'nick';

$playerIdFieldPartner = 'player_' . ($player === 1 ? '2' : '1');
$playerFieldPartner = 'player' . ($player === 1 ? '2' : '1');
$scoreFieldPartner = 'score_' . ($player === 1 ? 'two' : 'one');

$score = $duel->$scoreField;
$scorePartner = $duel->$scoreFieldPartner;

$isWinner = $duel->$playerIdField && $duel->$playerIdFieldPartner &&
    ($score + $scorePartner == $bestOf) && $score > $scorePartner;

$playerLink = $duel->teamMode ? '' : $duel->$playerField->external_link;

$classId = $classIds[$duel->$playerIdField] ?? 0;
//$classId = PlayerClass::findOne($duel->$playerIdField);




?>
<?php $cc = PlayerClass::findOne($classId);?>
<div class="group-item__result <?= $duel->$playerIdField && $duel->$playerIdField === $duel->winner_id ? 'active' : '' ?> js-group-result">
    <?php if ($playerLink && is_string($playerLink)) : ?>
        <a target="_blank" href="<?= $playerLink ?>" class="brackets-item__title no-decor" style="color: <?= $cc->avatar; ?> !important;">
            <?= Html::encode($duel->$playerField->$nameField) ?>
        </a>
    <?php else : ?>
        <div class="brackets-item__title"
             style="color: <?=  $cc->avatar;  ?> !important;"><?= Html::encode($duel->$playerField->$nameField) ?></div>
    <?php endif; ?>

    <div class="group-item__score"><?= $duel->$scoreField ?: '-' ?></div>
</div>