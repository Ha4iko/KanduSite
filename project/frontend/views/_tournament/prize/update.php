<?php

/** @var $this View */

/** @var $model Tournament */

use frontend\models\Tournament;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\models\TournamentPrize;

$specialPrizes = $model->getTournamentPrizesByType(TournamentPrize::TYPE_SPECIAL, true);
$secondaryPrizes = $model->getTournamentPrizesByType(TournamentPrize::TYPE_SECONDARY, true);

$existStandardPrizes = trim($model->prize_one) || trim($model->prize_two) ||
    trim($model->prize_three) || trim($model->prize_four);

$isEmpty = !$specialPrizes && !$secondaryPrizes && !$existStandardPrizes;
?>
    <div class="popup" id="adminAddPrizes">
        <div class="popup-wrap">
            <div class="popup-main">
                <?php Pjax::begin([
                    'id' => 'prizeForm',
                    'enablePushState' => false,
                    'enableReplaceState' => false,
                ]); ?>
                <?= Html::beginForm(['/prize/update', 'id' => $model->id], 'post', [
                    'data-pjax' => 1,
                    'enctype' => 'multipart/form-data',
                ]); ?>
                <?= Html::hiddenInput($model->formName() . '[id]', $model->id) ?>
                <div class="popup-head">
                    <div class="popup-close js-popup-close"><a class="link-back" href="#">
                            <svg class="icon">
                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>
                            close</a></div>
                    <div class="popup-title h3"><?= $isEmpty ? 'Add' : 'Edit' ?> Prizes</div>
                </div>
                <div class="popup-content">

                    <div class="content-block">
                        <h6 class="content-block__title">Standard Prizes</h6>
                        <div class="add-prizes">

                            <div class="add-prize <?= $model->prize_one ? '' : 'disabled' ?>" data-checkbox>
                                <div class="add-prize__checkbox">
                                    <div class="checkbox checkbox--toggler">
                                        <label class="checkbox-label" for="addPrize1">
                                            <input class="checkbox-input" type="checkbox"
                                                   id="addPrize1" <?= $model->prize_one ? 'checked="checked"' : '' ?>>
                                            <div class="checkbox-content">
                                                <div class="checkbox-style"></div>
                                                <div class="checkbox-text h6" data-text-in="enabled"
                                                     data-text-out="disabled">
                                                    <?= $model->prize_one ? 'enabled' : 'disabled' ?>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="add-prize__title">
                                    <div class="prop"><?= $model->getAttributeLabel('prize_one') ?></div>
                                </div>
                                <div class="add-prize__value">
                                    <?= Html::activeTextInput($model, 'prize_one', [
                                        'placeholder' => 'enter prize',
                                        'class' => 'field' . ($model->prize_one ? '' : ' disabled')
                                    ]) ?>
                                </div>
                            </div>

                            <div class="add-prize <?= $model->prize_two ? '' : 'disabled' ?>" data-checkbox>
                                <div class="add-prize__checkbox">
                                    <div class="checkbox checkbox--toggler">
                                        <label class="checkbox-label" for="addPrize2">
                                            <input class="checkbox-input" type="checkbox"
                                                   id="addPrize2" <?= $model->prize_two ? 'checked="checked"' : '' ?>>
                                            <div class="checkbox-content">
                                                <div class="checkbox-style"></div>
                                                <div class="checkbox-text h6" data-text-in="enabled"
                                                     data-text-out="disabled">
                                                    <?= $model->prize_two ? 'enabled' : 'disabled' ?>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="add-prize__title">
                                    <div class="prop"><?= $model->getAttributeLabel('prize_two') ?></div>
                                </div>
                                <div class="add-prize__value">
                                    <?= Html::activeTextInput($model, 'prize_two', [
                                        'placeholder' => 'enter prize',
                                        'class' => 'field' . ($model->prize_two ? '' : ' disabled')
                                    ]) ?>
                                </div>
                            </div>

                            <div class="add-prize <?= $model->prize_three ? '' : 'disabled' ?>" data-checkbox>
                                <div class="add-prize__checkbox">
                                    <div class="checkbox checkbox--toggler">
                                        <label class="checkbox-label" for="addPrize3">
                                            <input class="checkbox-input" type="checkbox"
                                                   id="addPrize3" <?= $model->prize_three ? 'checked="checked"' : '' ?>>
                                            <div class="checkbox-content">
                                                <div class="checkbox-style"></div>
                                                <div class="checkbox-text h6" data-text-in="enabled"
                                                     data-text-out="disabled">
                                                    <?= $model->prize_three ? 'enabled' : 'disabled' ?>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="add-prize__title">
                                    <div class="prop"><?= $model->getAttributeLabel('prize_three') ?></div>
                                </div>
                                <div class="add-prize__value">
                                    <?= Html::activeTextInput($model, 'prize_three', [
                                        'placeholder' => 'enter prize',
                                        'class' => 'field' . ($model->prize_three ? '' : ' disabled')
                                    ]) ?>
                                </div>
                            </div>

                            <div class="add-prize <?= $model->prize_four ? '' : 'disabled' ?>" data-checkbox>
                                <div class="add-prize__checkbox">
                                    <div class="checkbox checkbox--toggler">
                                        <label class="checkbox-label" for="addPrize4">
                                            <input class="checkbox-input" type="checkbox"
                                                   id="addPrize4" <?= $model->prize_four ? 'checked="checked"' : '' ?>>
                                            <div class="checkbox-content">
                                                <div class="checkbox-style"></div>
                                                <div class="checkbox-text h6" data-text-in="enabled"
                                                     data-text-out="disabled">
                                                    <?= $model->prize_four ? 'enabled' : 'disabled' ?>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="add-prize__title">
                                    <div class="prop"><?= $model->getAttributeLabel('prize_four') ?></div>
                                </div>
                                <div class="add-prize__value">
                                    <?= Html::activeTextInput($model, 'prize_four', [
                                        'placeholder' => 'enter prize',
                                        'class' => 'field' . ($model->prize_four ? '' : ' disabled')
                                    ]) ?>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="content-block dev-special">
                        <h6 class="content-block__title">special prizes</h6>
                        <div class="add append">
                            <table class="append-template" data-append="prize">
                                <tr class="append-item" data-append="prize">
                                    <td>
                                        <?= Html::hiddenInput('TournamentPrize[%i%][id]', null, []) ?>
                                        <?= Html::hiddenInput('TournamentPrize[%i%][tournament_id]', $model->id, []) ?>
                                        <?= Html::hiddenInput('TournamentPrize[%i%][type_id]', TournamentPrize::TYPE_SPECIAL, []) ?>
                                        <div class="add-cell">
                                            <?= Html::textInput('TournamentPrize[%i%][description]', '', [
                                                'placeholder' => 'enter name of prize',
                                                'class' => 'field'
                                            ]) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="add-cell">
                                            <?= Html::textInput('TournamentPrize[%i%][money]', '', [
                                                'placeholder' => 'enter prize',
                                                'class' => 'field'
                                            ]) ?>
                                        </div>
                                    </td>
                                    <td class="add-table__clear">
                                        <div class="clear">
                                            <a class="clear-btn js-add-clear" href="#" data-append="prize">
                                                <svg class="icon">
                                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-clear"></use>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div class="add-table">
                                <div class="table table--static">
                                    <div class="table-content">
                                        <div class="table-inner">
                                            <table>
                                                <tbody class="append-wrap" data-append="prize">
                                                <?php foreach ($specialPrizes as $iSpec => $prize) : ?>
                                                    <tr class="append-item" data-append="prize">
                                                        <td>
                                                            <?= Html::hiddenInput($prize->formName() . '[' . $iSpec . '][id]', $prize->id, []) ?>
                                                            <?= Html::hiddenInput($prize->formName() . '[' . $iSpec . '][tournament_id]', $model->id, []) ?>
                                                            <?= Html::hiddenInput($prize->formName() . '[' . $iSpec . '][type_id]', TournamentPrize::TYPE_SPECIAL, []) ?>
                                                            <div class="add-cell <?= $prize->hasErrors('description') ? 'error' : '' ?>">
                                                                <?= Html::textInput($prize->formName() . '[' . $iSpec . '][description]', $prize->description, [
                                                                    'placeholder' => 'enter name of prize',
                                                                    'class' => 'field',
                                                                ]) ?>
                                                                <?= Html::error($prize, 'description', ['class' => 'field-error']) ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="add-cell <?= $prize->hasErrors('money') ? 'error' : '' ?>">
                                                                <?= Html::textInput($prize->formName() . '[' . $iSpec . '][money]', $prize->money, [
                                                                    'placeholder' => 'enter prize',
                                                                    'class' => 'field',
                                                                ]) ?>
                                                                <?= Html::error($prize, 'money', ['class' => 'field-error']) ?>
                                                            </div>
                                                        </td>
                                                        <td class="add-table__clear">
                                                            <div class="clear">
                                                                <a class="clear-btn js-add-clear" href="#"
                                                                   data-append="prize">
                                                                    <svg class="icon">
                                                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-clear"></use>
                                                                    </svg>
                                                                </a>
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
                                    <a class="btn btn--md js-add-btn" href="#" data-append="prize">
                                        add one
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content-block dev-secondary">
                        <h6 class="content-block__title">secondary prizes</h6>
                        <div class="add append">
                            <table class="append-template" data-append="prize">
                                <tr class="append-item" data-append="prize">
                                    <td>
                                        <?= Html::hiddenInput('TournamentPrize[%j%][id]', null, []) ?>
                                        <?= Html::hiddenInput('TournamentPrize[%j%][tournament_id]', $model->id, []) ?>
                                        <?= Html::hiddenInput('TournamentPrize[%j%][type_id]', TournamentPrize::TYPE_SECONDARY, []) ?>
                                        <div class="add-cell">
                                            <?= Html::textInput('TournamentPrize[%j%][description]', '', [
                                                'placeholder' => 'enter name of prize',
                                                'class' => 'field'
                                            ]) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="add-cell">
                                            <?= Html::textInput('TournamentPrize[%j%][money]', '', [
                                                'placeholder' => 'enter prize',
                                                'class' => 'field'
                                            ]) ?>
                                        </div>
                                    </td>

                                    <td class="add-table__clear">
                                        <div class="clear">
                                            <a class="clear-btn js-add-clear" href="#" data-append="prize">
                                                <svg class="icon">
                                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-clear"></use>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div class="add-table">
                                <div class="table table--static">
                                    <div class="table-content">
                                        <div class="table-inner">
                                            <table>
                                                <tbody class="append-wrap" data-append="prize">
                                                <?php foreach ($secondaryPrizes as $iSec => $prize) :
                                                    $iSec = 100 + $iSec;
                                                    ?>
                                                    <tr class="append-item" data-append="prize">
                                                        <td>
                                                            <?= Html::hiddenInput($prize->formName() . '[' . $iSec . '][id]', $prize->id, []) ?>
                                                            <?= Html::hiddenInput($prize->formName() . '[' . $iSec . '][tournament_id]', $model->id, []) ?>
                                                            <?= Html::hiddenInput($prize->formName() . '[' . $iSec . '][type_id]', TournamentPrize::TYPE_SECONDARY, []) ?>
                                                            <div class="add-cell <?= $prize->hasErrors('description') ? 'error' : '' ?>">
                                                                <?= Html::textInput($prize->formName() . '[' . $iSec . '][description]', $prize->description, [
                                                                    'placeholder' => 'enter name of prize',
                                                                    'class' => 'field'
                                                                ]) ?>
                                                                <?= Html::error($prize, 'description', ['class' => 'field-error']) ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="add-cell <?= $prize->hasErrors('money') ? 'error' : '' ?>">
                                                                <?= Html::textInput($prize->formName() . '[' . $iSec . '][money]', $prize->money, [
                                                                    'placeholder' => 'enter prize',
                                                                    'class' => 'field'
                                                                ]) ?>
                                                                <?= Html::error($prize, 'money', ['class' => 'field-error']) ?>
                                                            </div>
                                                        </td>
                                                        <td class="add-table__clear">
                                                            <div class="clear">
                                                                <a class="clear-btn js-add-clear" href="#"
                                                                   data-append="prize">
                                                                    <svg class="icon">
                                                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-clear"></use>
                                                                    </svg>
                                                                </a>
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
                                    <a class="btn btn--md js-add-btn" href="#" data-append="prize">
                                        add one
                                    </a>
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
    $(document).on('click', '.dev-special .js-add-btn', function() {
        updateSpecialIndices();
    });

    $(document).on('click', '.dev-special .js-add-clear', function() {
        updateSpecialIndices();
    });
    
    let updateSpecialIndices = function() { 
        $('.dev-special .append-wrap .append-item').each(function(index) {
            $(this).find('input, textarea').each(function() {
                $(this).attr('name', $(this).attr('name').replace(/\[(\d+|%i%)\]/g, '[' + index + ']'));
            });
        });
    }
    
    $(document).on('click', '.dev-secondary .js-add-btn', function() {
        updateSecondaryIndices();
    });

    $(document).on('click', '.dev-secondary .js-add-clear', function() {
        updateSecondaryIndices();
    });
    
    let updateSecondaryIndices = function() { 
        $('.dev-secondary .append-wrap .append-item').each(function(index) {
            $(this).find('input, textarea').each(function() {
                $(this).attr('name', $(this).attr('name').replace(/\[(\d+|%j%)\]/g, '[10' + index + ']'));
            });
        });
    };
    
    $(document).on('change', '.checkbox--toggler input[type=checkbox]', function() {
        let rowWrapper = $(this).closest("[data-checkbox]"),
            checkboxText = $(this).closest(".checkbox").find(".checkbox-text");
        
        if ($(this).prop("checked")) {
            if ("undefined" !== typeof(checkboxText.attr("data-text-in"))) {
                checkboxText.text(checkboxText.attr("data-text-in"));
                rowWrapper.removeClass("disabled");
                rowWrapper.find("input").not($(this)).removeClass("disabled");
                //rowWrapper.find(".sort").removeClass("disabled"); // from main.js 
            }
        } else {
            if ("undefined" !== typeof(checkboxText.attr("data-text-out"))) {
                checkboxText.text(checkboxText.attr("data-text-out"));
                rowWrapper.addClass("disabled");
                rowWrapper.find("input").not($(this)).addClass("disabled").val('');
                //rowWrapper.find(".sort").addClass("disabled"); // from main.js 
            }
        }
    }); 
    
JS;
$this->registerJs($js);