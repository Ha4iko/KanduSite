<?php

use yii\web\View;
use yii\helpers\Html;
use frontend\models\ParticipantsWithTeamsForm;

/** @var $this View */
/** @var $model ParticipantsWithTeamsForm */

$teamOffset = $teamOffset ?? 0;
$teamName = $teamName ?? '';
?>
<div class="append-template" data-append="team">
    <div class="content-block append-item" data-append="team">
        <h6 class="content-block__title">team #0</h6>
        <div class="add append">
            <?= $this->render('_update-team_template_player', [
                'model' => $model,
                'classes' => $classes,
                'races' => $races,
                'factions' => $factions,
                'worlds' => $worlds,
                'teamOffset' => $teamOffset,
                'teamName' => $teamName,
            ]) ?>
            <div class="mb--sm">
                <div class="add-table">
                    <div class="table table--full table--static">
                        <div class="table-content">
                            <div class="table-inner">
                                <table>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <div class="add-cell">
                                                <?= Html::textInput('teamNamesByOffset[' . '0' . '][name]', null, [
                                                    'placeholder' => 'enter name of team',
                                                    'class' => 'field autocomplete-field js-team-autocomplete'
                                                ]) ?>
                                                <?= Html::hiddenInput('teamNamesByOffset[' . '0' . '][id]', null) ?>
                                                <div class="js-name-error" style="color: #DF0D14;"></div>
                                            </div>

                                        </td>
                                        <td class="add-table__clear">
                                            <div class="clear">
                                                <a class="clear-btn js-custom-add-clear" href="#" data-append="team">
                                                    <svg class="icon">
                                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-clear"></use>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="add-table">
                <div class="table">
                    <div class="table-content">
                        <div class="table-inner">
                            <table>
                                <thead>
                                <tr>
                                    <th>player Nickname <span>*</span></th>
                                    <th>class <span>*</span></th>
                                    <th>race <span>*</span></th>
                                    <th>faction</th>
                                    <th>game world</th>
                                    <th class="add-table__clear"></th>
                                </tr>
                                </thead>
                                <tbody class="append-wrap" data-append="player">
                                <?php /*
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
                                            <div class="select select--md">
                                                <div class="select-btn">
                                                    <?= Html::dropDownList('TournamentToPlayer[%i%][world_id]', null,
                                                        $worlds,
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
                                */ ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="add-controls">
                <div class="add-controls__btn">
                    <a class="btn btn--md js-add-btn" href="#" data-append="player">
                        add one more player to team
                    </a>
                </div>
                <div class="add-controls__main">
                    <div class="add-controls__divider prop">or</div>
                    <div class="add-controls__content">
                        <div class="add-controls__field">
                            <input class="field field--md js-parser-source" type="text" placeholder="insert link to add">
                            <div class="js-parser-answer" style="color: #DF0D14; width: 100%;"></div>
                        </div>
                        <div class="add-controls__btn">
                            <button type="button" class="btn btn--md js-parser-runner" data-pjax="0">
                                Add Data from link
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>