<?php

use frontend\models\BracketRelegationParticipantsForm;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var $this View */
/** @var $model BracketRelegationParticipantsForm */

$bracketParticipantsIds = $model->bracketParticipantsIds;
$tournamentParticipants = $model->tournamentParticipantsPlayers;

?>

<div class="popup" id="adminBracketsInsertParticipants1">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'bracketTableParticipantsForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(['/bracket-relegation/update-players', 'id' => $model->tournament->id, 'bracketId' => $model->bracketId], 'post', [
                'data-pjax' => 1,
                'enctype' => 'multipart/form-data',
            ]); ?>
            <?= Html::hiddenInput($model->formName() . '[bracketId]', $model->bracketId, []) ?>

            <div class="popup-head">
                <div class="popup-close js-popup-close">
                    <a class="link-back" href="#">
                        <svg class="icon">
                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                        </svg>
                        close
                    </a>
                </div>
                <div class="popup-title h3">
                    <?= $bracketParticipantsIds ? 'Edit' : 'Insert' ?> participants <span>/ <?= Html::encode($model->bracket->title) ?></span>
                </div>
            </div>

            <div class="popup-content">
                <div class="content-block">
                    <div class="control">
                        <div class="control-side">
                            <div class="prop">find player</div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field">
                                    <input class="field" type="text" placeholder="nickname">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-block">
                    <div class="control control--top">
                        <div class="control-side">
                            <div class="prop">Enabled count of players</div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field">
                                    <div class="control-info">
                                        <div class="control-info__value">
                                            <span <?= (false) ? 'class="error"' : '' ?>>0</span> of <?= $model->bracket->best_of ?>
                                        </div>
                                        <div class="control-info__help">
                                            <div class="text--sm">Тебе необходимо уменьшить количество допущенных к турниру игроков</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-block">
                    <div class="table table--control">
                        <div class="table-content">
                            <div class="table-inner">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="checkbox">
                                                    <label class="checkbox-label" for="insertParticipantsAll">
                                                        <input class="checkbox-input" type="checkbox" id="insertParticipantsAll">
                                                        <div class="checkbox-content">
                                                            <div class="checkbox-style"></div>
                                                            <div class="checkbox-text h6">select all</div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </th>
                                            <th>Nickname</th>
                                            <th>class</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    <?php foreach ($tournamentParticipants as $participantId => $participant) :
                                        $isAttached = isset($bracketParticipantsIds[$participantId]); ?>
                                        <tr <?= $isAttached ? '' : 'class="disabled"' ?> data-checkbox>
                                            <td>
                                                <div class="checkbox checkbox--toggler">
                                                    <label class="checkbox-label" for="insertParticipants<?= $participantId ?>">
                                                        <input class="checkbox-input" type="checkbox" id="insertParticipants<?= $participantId ?>"
                                                            name="<?= 'Participant[' . $participantId . '][active]' ?>"
                                                            <?= $isAttached ? 'checked="checked"' : '' ?>>

                                                        <div class="checkbox-content">
                                                            <div class="checkbox-style"></div>
                                                            <div class="checkbox-text h6" data-text-in="enabled" data-text-out="disabled">
                                                                <?= $isAttached ? 'enabled' : 'disabled' ?>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </td>

                                            <td>
                                                <?= Html::hiddenInput('Participant[' . $participantId . '][id]', $participantId, []) ?>
                                                <div class="table-player">
                                                    <div class="table-player__avatar">
                                                        <img src="<?= $participant['player_avatar'] ?>" alt="">
                                                    </div>
                                                    <div class="table-player__name">
                                                        <?= Html::encode($participant['player_nick']) ?>
                                                    </div>
                                                </div>
                                            </td>

                                            <td><?= Html::encode($participant['player_class']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="a-footer a-footer--start">
                <button class="btn" type="submit">save and continue</button>

                <a class="btn js-popup-close" href="#">cancel</a>
            </div>

            <?= Html::endForm(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$js = <<<JS
    $(document).on('change', '#insertParticipantsAll', function() {
        $('#bracketTableParticipantsForm [data-checkbox] input[type=checkbox]').prop('checked', $(this).prop('checked')).change();
    });

    $(document).on('change', '[data-checkbox] input[type=checkbox]', function() {
        let thisCheckbox = $(this),
            rowWrapper = thisCheckbox.closest("[data-checkbox]"),
            checkboxText = rowWrapper.find(".checkbox-text");
        
        if ($(this).prop("checked")) {
            if ("undefined" !== typeof(checkboxText.attr("data-text-in"))) {
                checkboxText.text(checkboxText.attr("data-text-in"));
                rowWrapper.removeClass("disabled");
                rowWrapper.find("input").not($(this)).removeClass("disabled");
            } 
        } else { 
            if ("undefined" !== typeof(checkboxText.attr("data-text-out"))) {
                checkboxText.text(checkboxText.attr("data-text-out"));
                rowWrapper.addClass("disabled");
                rowWrapper.find("input").not($(this)).addClass("disabled");
            }
        }
    }); 

JS;
$this->registerJs($js);