<?php

use frontend\models\BracketSwissDuelsForm;
use yii\web\View;
use yii\helpers\Html;
use common\models\Bracket\Swiss;
use common\models\Bracket\Swiss\Round;
use common\models\Bracket\Swiss\Duel;
use common\models\PlayerClass;

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

$playerIdFieldPartner = 'player_' . ($player === 1 ? '2' : '1');
$playerFieldPartner = 'player' . ($player === 1 ? '2' : '1');
$scoreFieldPartner = 'score_' . ($player === 1 ? 'two' : 'one');

$nameField = $duel->teamMode ? 'name' : 'nick';
$score = $duel->$scoreField;
$scorePartner = $duel->$scoreFieldPartner;

$isWinner = $duel->$playerIdField && $duel->$playerIdFieldPartner &&
    ($score + $scorePartner == $bestOf) && $score > $scorePartner;

$classId = $classIds[$duel->$playerIdField] ?? 0;
?>
<?php $cc = PlayerClass::findOne($classId);?>
<div class="group-item__result <?= $isWinner ? 'active' : '' ?> js-group-result">
    <?php if ($canSetPlayer): ?>
        <div class="group-item__select">
            <div class="select select--sm">
                <div class="select-btn">
                    <?= Html::dropDownList('Duels[' . $index .'][' . $playerIdField . ']', $duel->$playerIdField,
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
        <div class="brackets-item__title"
             style="color: <?= $cc->avatar; ?> !important;"
        >
            <?php if (isset($duel->$playerField->external_link) && $duel->$playerField->external_link): ?>
                <a href="<?= $duel->$playerField->external_link ?>" class="no-decor" target="_blank" style="color: <?= $cc->avatar; ?> !important;">
                    <?= Html::encode($duel->$playerField->$nameField) ?>
                </a>
            <?php else: ?>
                <?= Html::encode($duel->$playerField->$nameField) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (!$canSetScore): ?>
        <div class="group-item__score <?= $duel->active ? 'non-editable' : '' ?>"><?= $score ?? '-' ?></div>
    <?php else: ?>
        <?= Html::input('number', 'Duels[' . $index . '][' . $scoreField . ']', $score,
            [
                'class' => 'group-item__score js-group-score field' . ($duel->model->hasErrors() ? ' has-error' : ''),
                'placeholder' => '-',
                'data-best-of' => $bestOf,
                'autocomplete' => 'off'
            ]) ?>
    <?php endif; ?>
</div>