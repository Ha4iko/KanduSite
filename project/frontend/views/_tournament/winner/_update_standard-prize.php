<?php

use frontend\models\WinnersForm;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/** @var $this View */
/** @var $model WinnersForm */
/** @var $i int */
/** @var $owners array */
/** @var $isTeam bool */

$suffixStandardPrize = '';
if ($i === 1) $suffixStandardPrize = 'one';
if ($i === 2) $suffixStandardPrize = 'two';
if ($i === 3) $suffixStandardPrize = 'three';
if ($i === 4) $suffixStandardPrize = 'four';

$attrPrize = 'prize_' . $suffixStandardPrize;
$attrPlayer = 'player_' . $suffixStandardPrize;

?>
<?php if (trim($model->$attrPrize)) : ?>
<div class="prizes--sm prizes--admin">
    <div class="prize">
        <picture>
            <source srcset="<?= IMG_ROOT ?>/prize<?= $i ?>.webp" type="<?= IMG_ROOT ?>/webp">
            <source srcset="<?= IMG_ROOT ?>/prize<?= $i ?>.jpg">
            <img class="prize-img" src="<?= IMG_ROOT ?>/prize<?= $i ?>.jpg" alt="">
        </picture>
        <div class="prize-inner">
            <div class="prize-content">
                <div class="h4 prize-value"><?= $model->$attrPrize ?></div>
                <div class="h6 prize-place"><?= $model->getTournamentAttributeLabel($attrPrize) ?></div>
            </div>
        </div>
    </div>
    <div class="prizes-select">
        <div class="select select--md">
            <div class="select-btn">
                <?= Html::activeDropDownList($model, $attrPlayer,
                    ArrayHelper::merge([null => ''], $owners),
                    [
                        'prompt' => 'choose',
                        'data-placeholder' => 'choose ' . ($isTeam ? 'team' : 'player'),
                        'data-style' => '2',
                        'class' => 'js-custom-select',
                        'data-drop' => 'select--md first-null',
                    ]
                ) ?>
            </div>
            <div class="select-drop">
                <div class="close js-close">
                    <div class="close-inner">
                        <div class="close-icon">
                            <svg class="icon">
                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>
                        </div>
                        <div class="close-text">close</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>