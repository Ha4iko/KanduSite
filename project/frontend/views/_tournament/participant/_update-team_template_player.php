<?php

use yii\web\View;
use yii\helpers\Html;
use frontend\models\ParticipantsWithTeamsForm;
use frontend\models\TournamentToPlayer;
use frontend\models\Team;

/** @var $this View */
/** @var $teamOffset int */
/** @var $model ParticipantsWithTeamsForm */
/** @var $teamName string */

$teamName = $teamName ?? '';
?>

<table class="append-template" data-append="player">
    <tr class="append-item" data-append="player" data-offset="<?= $teamOffset ?>">
        <td>
            <?= Html::hiddenInput('TournamentToPlayer[%i%][id]', null, []) ?>
            <?= Html::hiddenInput('TournamentToPlayer[%i%][tournament_id]', $model->id, []) ?>
            <?= Html::hiddenInput('TournamentToPlayer[%i%][postTeamOffset]', $teamOffset, []) ?>
            <?= Html::hiddenInput('TournamentToPlayer[%i%][teamName]', $teamName, []) ?>
            <div class="add-cell">
                <div class="autocomplete">
                    <?= Html::textInput('TournamentToPlayer[%i%][playerNick]', null, [
                        'placeholder' => 'enter',
                        'class' => 'field field--md autocomplete-field js-player-autocomplete'
                    ]) ?>
                    <div class="autocomplete-arrow">
                        <svg class="icon">
                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                        </svg>
                    </div>
                </div>
                <div class="js-name-error" style="color: #DF0D14;"></div>
            </div>
        </td>
        <td>
            <div class="add-cell">
                <div class="select select--md">
                    <div class="select-btn">
                        <?= Html::dropDownList('TournamentToPlayer[%i%][class_id]', null,
                            $classes,
                            [
                                'class' => 'js-select',
                                'data-placeholder' => 'choose',
                                'data-style' => '2',
                                'data-drop' => 'select--md',
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
        </td>
        <td>
            <div class="add-cell">
                <div class="select select--md">
                    <div class="select-btn">
                        <?= Html::dropDownList('TournamentToPlayer[%i%][race_id]', null,
                            $races,
                            [
                                'class' => 'js-select',
                                'data-placeholder' => 'choose',
                                'data-style' => '2',
                                'data-drop' => 'select--md',
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
        </td>
        <td>
            <div class="add-cell">
                <div class="select select--md">
                    <div class="select-btn">
                        <?= Html::dropDownList('TournamentToPlayer[%i%][faction_id]', null,
                            $factions,
                            [
                                'class' => 'js-select',
                                'data-placeholder' => 'choose',
                                'data-style' => '2',
                                'data-drop' => 'select--md',
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
        </td>
        <td>
            <div class="add-cell">
                <div class="autocomplete">
                    <?= Html::textInput('TournamentToPlayer[%i%][worldName]', null, [
                        'placeholder' => 'enter',
                        'class' => 'field field--md js-world-autocomplete'
                    ]) ?>
                    <div class="autocomplete-arrow">
                        <svg class="icon">
                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </td>
        <td class="add-table__clear">
            <div class="clear">
                <a class="clear-btn js-custom-add-clear" href="#" data-append="player">
                    <svg class="icon">
                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-clear"></use>
                    </svg>
                </a>
            </div>
        </td>
    </tr>
</table>
