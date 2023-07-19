<?php

use frontend\models\BracketTableParticipantsForm;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var $this View */
/** @var $model BracketTableParticipantsForm */

$bracketTableRows = $model->bracketTableRows;
$bracketParticipants = $model->bracketParticipants;

$rowsParticipantsIds = [];
foreach ($bracketTableRows as $bracketTableRow) {
    $rowsParticipantsIds[$bracketTableRow->tournament_to_player_id] = $bracketTableRow->id;
}
?>

<div class="popup" id="adminBracketsInsertParticipants1">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'bracketTableParticipantsForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(['/bracket-table/update-bracket-table-participants', 'id' => $model->tournament_id, 'bracketId' => $model->id], 'post', [
                'data-pjax' => 1,
                'enctype' => 'multipart/form-data',
                'class' => 'bracket-participants-form',
            ]); ?>
            <?= Html::hiddenInput($model->formName() . '[id]', $model->id, []) ?>

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
                    <?= $bracketTableRows ? 'Edit' : 'Insert' ?> participants <span>/ <?= Html::encode($model->title) ?></span>
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
                                    <input class="field" name="filterParticipants" type="text" placeholder="nickname">                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-block">
                    <div class="table table--control">
                        <div class="table-content">
                            <div class="table-inner">
                                <?php if (empty($bracketParticipants)) : ?>
                                    <p style="color: #DF0D14;">First add participants of tournament</p>
                                <?php else : ?>
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
                                        <?php foreach ($bracketParticipants as $participantId => $participant) :
                                            $isAttached = isset($rowsParticipantsIds[$participantId]); ?>
                                            <tr <?= $isAttached ? '' : 'class="disabled"' ?> data-checkbox>
                                                <td>
                                                    <div class="checkbox checkbox--toggler">
                                                        <label class="checkbox-label" for="insertParticipants<?= $participantId ?>">
                                                            <input class="checkbox-input" type="checkbox" id="insertParticipants<?= $participantId ?>"
                                                                name="<?= 'BracketTableRow[' . $participantId . '][active]' ?>"
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
                                                    <?= Html::hiddenInput('BracketTableRow[' . $participantId . '][id]', $rowsParticipantsIds[$participantId] ?? null , []) ?>
                                                    <?= Html::hiddenInput('BracketTableRow[' . $participantId . '][tournament_to_player_id]', $participantId, []) ?>
                                                    <div class="table-player">
                                                        <div class="table-player__avatar">
                                                            <img src="<?= $participant['player_avatar'] ?>" alt="">
                                                        </div>
                                                        <div class="table-player__name js-filter-by">
                                                            <?= Html::encode($participant['player_nick']) ?>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td><?= Html::encode($participant['player_class']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="a-footer a-footer--start">
                <?php if (!empty($bracketParticipants)) : ?>
                    <button class="btn" type="submit">save and continue</button>
                <?php endif; ?>

                <a class="btn js-popup-close" href="#">cancel</a>
            </div>

            <?= Html::endForm(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$js = <<<JS
    $(document).on('keyup', '[name=filterParticipants]', function() {
        let searchValue = $(this).val();
        
        $('.bracket-participants-form tr[data-checkbox]').each(function() {
            let thisRow = $(this); 
            if (thisRow.find('.js-filter-by').text().search(new RegExp(searchValue, "i")) > -1) {
                thisRow.show();
            } else {
                thisRow.hide();
            }
        });
    });

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