<?php

use frontend\models\WinnersForm;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\TournamentPrize;
use yii\widgets\Pjax;

/** @var $this View */
/** @var $model WinnersForm */
/** @var $i int */
/** @var $owners array */
/** @var $prize TournamentPrize */
/** @var $isTeam bool */

$attribute = $model->formName() . "[prizes][{$prize->id}]";
?>
<div class="prizes--sm prizes--admin">
    <div class="prize">
        <div class="prize-inner">
            <div class="prize-content">
                <div class="h4 prize-value"><?= Html::encode($prize->money) ?></div>
                <div class="h6 prize-place"><?= Html::encode($prize->description) ?></div>
            </div>
        </div>
    </div>
    <div class="prizes-select">
        <div class="select select--md">
            <div class="select-btn">
                <?= Html::dropDownList($attribute, $model->prizes[$prize->id] ?? null,
                    ArrayHelper::merge([null => ''], $owners),
                    [
                        'prompt' => 'choose',
                        'data-placeholder' => 'choose ' . ($isTeam ? 'team' : 'player'),
                        'data-style' => '2',
                        'class' => 'js-custom-select js-second-mark',
                        'data-drop' => 'first-null',
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
