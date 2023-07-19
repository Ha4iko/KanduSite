<?php

use frontend\models\BracketRelegationParticipantsForm;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var $this View */
/** @var $model BracketRelegationParticipantsForm */
/** @var $tournamentParticipants array */

$attachedParticipantsIds = $model->attachedParticipantsIds;

$this->registerJs("
function refreshAttachedIndicator() {
        let attachedCount = $('.bracket-participants-form [data-checkbox] input[type=checkbox]:checked').length,
            indicator = $('.js-attached-count'),
            attachedMessage = $('.js-attached-message'),
            formSubmit = $('.js-participants-submit'),
            attachedMax = parseInt(indicator.attr('data-max'));
        
        indicator.attr('data-attached', attachedCount).text(attachedCount);
        if (attachedCount !== attachedMax) {
            indicator.addClass('error');
            if (attachedCount < attachedMax) {
                attachedMessage.text('You need increase count of participants');
            } else {
                attachedMessage.text('You need decrease count of participants');
            }
            formSubmit.addClass('waiting').attr('type', 'button');
        } else {
            indicator.removeClass('error');
            attachedMessage.text('');
            formSubmit.removeClass('waiting').attr('type', 'submit');
        }
    }
");
?>

<div class="popup" id="adminBracketsInsertParticipants1">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'bracketRelegationParticipantsForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(['/bracket-relegation/update-participants', 'id' => $model->tournament->id, 'bracketId' => $model->bracketId], 'post', [
                'data-pjax' => 1,
                'enctype' => 'multipart/form-data',
                'class' => 'bracket-participants-form',
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
                    <?= $attachedParticipantsIds ? 'Edit' : 'Insert' ?> participants <span>/ <?= Html::encode($model->bracket->title) ?></span>
                </div>
            </div>

            <div class="popup-content">
                <div class="content-block">
                    <div class="control">
                        <div class="control-side">
                            <div class="prop">find participant</div>
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
                    <?= ($modelErrors = $model->getErrorSummary(true))
                        ? Html::tag('div', implode('<br>', $modelErrors), ['class' => 'control', 'style' => 'color: #DF0D14;'])
                        : '' ?>

                    <div class="control control--top">
                        <div class="control-side">
                            <div class="prop">Enabled count of participants</div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field">
                                    <div class="control-info">
                                        <div class="control-info__value">
                                            <span data-max="<?= $model->bracket->participants ?>"
                                                  data-attached="0"
                                                  class="js-attached-count <?= (false) ? 'error' : '' ?>">0</span> of <?= $model->bracket->participants ?>
                                        </div>
                                        <div class="control-info__help">
                                            <div class="text--sm js-attached-message"></div>
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

                                <?= $this->render(
                                    $model->bracket->tournament->type->team_mode ? '_teams' : '_players', [
                                        'tournamentParticipants' => $tournamentParticipants,
                                        'attachedParticipantsIds' => $attachedParticipantsIds,
                                ]) ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="a-footer a-footer--start">
                <button class="btn js-participants-submit" type="submit">save and continue</button>

                <a class="btn js-popup-close" href="#">cancel</a>
            </div>

            <?php $this->registerJs("refreshAttachedIndicator();"); ?>

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
        $('.bracket-participants-form [data-checkbox] input[type=checkbox]').prop('checked', $(this).prop('checked')).change();
    });

    $(document).on('change', '.bracket-participants-form [data-checkbox] input[type=checkbox]', function() {
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
        
        refreshAttachedIndicator();
    }); 
   
    
JS;
$this->registerJs($js);