<?php

use yii\web\View;
use yii\helpers\Html;
use frontend\models\ParticipantsWithTeamsForm;
use frontend\models\TournamentToPlayer;
use frontend\models\TournamentToTeam;
use frontend\models\Team;

/** @var $this View */
/** @var $model ParticipantsWithTeamsForm */
/** @var $participantsLinksGroupedByTeam array */

$teamNumber = 0;
$usedParticipantIds = $model->getParticipantIdsInAllBrackets();

$teamIdToTtpId = [];
foreach (TournamentToTeam::findAll(['tournament_id' => $model->id]) as $ttt) {
    if ($ttt->team_id) $teamIdToTtpId[$ttt->team_id] = $ttt->id;
}
$isEmpty = isset($participantsLinksGroupedByTeam[0][0])
    && count($participantsLinksGroupedByTeam) === 1
    && count($participantsLinksGroupedByTeam[0]) === 1;
?>
<div class="content-block append-wrap" data-append="team">
    <?php foreach ($participantsLinksGroupedByTeam as $teamId => $participantsLinks) :
        /* @var $participantsLinks TournamentToPlayer[] */
        $teamNumber++;
        $teamOffset = $teamNumber * 100;
        $team = Team::findOne($teamId);
        $teamNameHasError = Yii::$app->request->isPost && !trim($team->name);
        ?>
    <div class="content-block append-item" data-append="team">
        <h6 class="content-block__title">team #<?= $teamNumber ?></h6>
        <div class="add append">
            <?= $this->render('_update-team_template_player', [
                'model' => $model,
                'classes' => $classes,
                'races' => $races,
                'factions' => $factions,
                'worlds' => $worlds,
                'teamOffset' => $teamOffset,
                'teamName' => is_object($team) ? $team->name : '',
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
                                            <div class="add-cell <?= $teamNameHasError ? 'error' : '' ?>">
                                                <div class="autocomplete">
                                                    <?= Html::textInput('teamNamesByOffset[' . $teamOffset . '][name]', is_object($team) ? $team->name : '', [
                                                        'placeholder' => 'enter name of team',
                                                        'class' => 'field autocomplete-field js-team-autocomplete'
                                                    ]) ?>
                                                    <?= Html::hiddenInput('teamNamesByOffset[' . $teamOffset . '][id]', TournamentToTeam::getRelationIdByTeamId($teamId)) ?>
                                                    <div class="autocomplete-arrow">
                                                        <svg class="icon">
                                                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <?= $teamNameHasError ? "<div class=\"field-error\" style=\"color: #DF0D14; display:block;\">Team name cannot be blank.</div>" : '' ?>
                                                <div class="js-name-error" style="color: #DF0D14;"></div>
                                            </div>
                                        </td>
                                        <td class="add-table__clear">
                                            <div class="clear">
                                                <?php if (!isset($usedParticipantIds[$teamIdToTtpId[$team->id]])) : ?>
                                                <a class="clear-btn js-custom-add-clear" href="#" data-append="team">
                                                    <svg class="icon">
                                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-clear"></use>
                                                    </svg>
                                                </a>
                                                <?php endif; ?>
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
                                <?php foreach ($participantsLinks as $i => $participantLink) :
                                    if (is_object($participantsLinks)) continue;
                                    if ($isEmpty) continue;
                                    $iInTeam = $teamOffset + $i;
                                ?>
                                <tr class="append-item" data-append="player" data-offset="<?= $teamOffset ?>">
                                    <td>
                                        <?= Html::hiddenInput($participantLink->formName() . '[' . $iInTeam . '][id]', $participantLink->id, []) ?>
                                        <?= Html::hiddenInput($participantLink->formName() . '[' . $iInTeam . '][tournament_id]', $participantLink->tournament_id, []) ?>
                                        <?= Html::hiddenInput($participantLink->formName() . '[' . $iInTeam . '][postTeamOffset]', $teamOffset, []) ?>
                                        <?= Html::hiddenInput($participantLink->formName() . '[' . $iInTeam . '][teamName]', $participantLink->teamName, []) ?>

                                        <div class="add-cell <?= $participantLink->hasErrors('playerNick') ? 'error' : '' ?>">
                                            <div class="autocomplete">
                                                <?= Html::textInput($participantLink->formName() . '[' . $iInTeam . '][playerNick]', $participantLink->getPlayerNick(), [
                                                    'placeholder' => 'enter',
                                                    'class' => 'field field--md autocomplete-field js-player-autocomplete'
                                                ]) ?>
                                                <div class="autocomplete-arrow">
                                                    <svg class="icon">
                                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                    </svg>
                                                </div>
                                            </div>
                                            <?= Html::error($participantLink, 'playerNick', ['class' => 'field-error', 'style' => 'color: #DF0D14; display:block;']) ?>
                                            <div class="js-name-error" style="color: #DF0D14;"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="add-cell <?= $participantLink->hasErrors('class_id') ? 'error' : '' ?>">
                                            <div class="select select--md">
                                                <div class="select-btn">
                                                    <?= Html::dropDownList($participantLink->formName() . '[' . $iInTeam . '][class_id]', $participantLink->class_id,
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
                                            <?= Html::error($participantLink, 'class_id', ['class' => 'field-error', 'style' => 'color: #DF0D14; display:block;']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="add-cell <?= $participantLink->hasErrors('race_id') ? 'error' : '' ?>">
                                            <div class="select select--md">
                                                <div class="select-btn">
                                                    <?= Html::dropDownList($participantLink->formName() . '[' . $iInTeam . '][race_id]', $participantLink->race_id,
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
                                            <?= Html::error($participantLink, 'race_id', ['class' => 'field-error', 'style' => 'color: #DF0D14; display:block;']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="add-cell">
                                            <div class="select select--md">
                                                <div class="select-btn">
                                                    <?= Html::dropDownList($participantLink->formName() . '[' . $iInTeam . '][faction_id]', $participantLink->faction_id,
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
                                        <div class="add-cell <?= $participantLink->hasErrors('worldName') ? 'error' : '' ?>">
                                            <div class="autocomplete">
                                                <?= Html::textInput($participantLink->formName() . '[' . $iInTeam . '][worldName]', $participantLink->worldName, [
                                                    'placeholder' => 'enter',
                                                    'class' => 'field field--md js-world-autocomplete'
                                                ]) ?>
                                                <div class="autocomplete-arrow">
                                                    <svg class="icon">
                                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                    </svg>
                                                </div>
                                            </div>
                                            <?= Html::error($participantLink, 'worldName', ['class' => 'field-error']) ?>
                                        </div>
                                    </td>
                                    <td class="add-table__clear">
                                        <div class="clear">
                                            <?php if (!isset($usedParticipantIds[$participantLink->id])) : ?>
                                            <a class="clear-btn js-custom-add-clear" href="#" data-append="player">
                                                <svg class="icon">
                                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-clear"></use>
                                                </svg>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
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
    <?php endforeach; ?>
</div>