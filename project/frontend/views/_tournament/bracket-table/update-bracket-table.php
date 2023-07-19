<?php

use frontend\models\Tournament;
use frontend\models\BracketTableForm;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var $this View */
/** @var $model BracketTableForm */

$bracketColumns = $model->getBracketColumnsAppended();
$isActive = 0;
foreach ($bracketColumns as $col)
    if ($col->active) $isActive++;

$teamMode = ($type = $model->tournament->type) ? boolval($type->team_mode) : false;
?>
<div class="popup" id="adminAddBracketTable">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'bracketTableForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(['/bracket-table/update-bracket-table', 'id' => $model->tournament_id, 'bracketId' => $model->id], 'post', [
                'data-pjax' => 1,
                'enctype' => 'multipart/form-data',
            ]); ?>
            <?= Html::hiddenInput($model->formName() . '[id]', $model->id, []) ?>

            <div class="popup-head">
                <div class="popup-close js-popup-close">
                    <a class="link-back" href="#">
                        <svg class="icon">
                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                        </svg>close
                    </a>
                </div>
                <div class="popup-title h3"><?= $model->isNewRecord ? 'Add' : 'Edit' ?> bracket <span>/ table</span></div>
            </div>

            <div class="popup-content js-column-del-prompt" style="display: none;">
                <div class="content-block">
                    <div class="popup-title h3 primary">Column deletion. Are you sure?</div>
                </div>
            </div>

            <div class="popup-content js-table-columns">
                <div class="content-block">
                    <div class="control">
                        <div class="control-side">
                            <div class="prop">Name of table <span>*</span></div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field <?= $model->hasErrors('title') ? 'error' : '' ?>">
                                    <?= Html::activeTextInput($model, 'title', [
                                        'placeholder' => 'enter name of table',
                                        'class' => 'field'
                                    ]) ?>
                                    <?= Html::error($model, 'title', ['class' => 'field-error']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-block">
                    <h5 class="content-block__title">enabled columns</h5>
                    <div class="controls sort-rows">
                        <?= $this->render( $teamMode ? 'update-bracket-table-teams_preset_rows' : 'update-bracket-table_preset_rows') ?>

                        <?php foreach ($bracketColumns as $i => $bracketColumn) :
                            if($bracketColumn['title'] != 'top'): ?>
                            <div class="control sort-row<?= $bracketColumn->active ? '' : ' disabled' ?>" data-active-checkbox>
                                <div class="control-side">
                                    <div class="checkbox checkbox--toggler">
                                        <label class="checkbox-label" for="tableColumn<?= $i ?>">
                                            <input class="checkbox-input" type="checkbox" name="<?= $bracketColumn->formName() . '[' . $i . '][active]' ?>"
                                                   id="tableColumn<?= $i ?>" <?= $bracketColumn->active ? 'checked="checked"' : '' ?>>

                                            <div class="checkbox-content">
                                                <div class="checkbox-style"></div>
                                                <div class="checkbox-text h6" data-text-in="enabled" data-text-out="disabled">
                                                    <?= $bracketColumn->active ? 'enabled' : 'disabled' ?>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="control-content">
                                    <div class="control-fields">
                                        
                                        <?= Html::hiddenInput($bracketColumn->formName() . '[' . $i . '][id]', $bracketColumn->id, []) ?>
                                        <?= Html::hiddenInput($bracketColumn->formName() . '[' . $i . '][column_type]', 0, []) ?>
                                        <?= Html::hiddenInput($bracketColumn->formName() . '[' . $i . '][order]', $bracketColumn->order, [
                                            'class' => 'dev-append-item-order',
                                        ]) ?>

                                        <div class="control-field<?= $bracketColumn->hasErrors('title') ? ' error' : '' ?>">
                                            <?= Html::textInput($bracketColumn->formName() . '[' . $i . '][title]', $bracketColumn->title, [
                                                'placeholder' => 'new row',
                                                'class' => 'field' . ($bracketColumn->active ? '' : ' disabled')
                                            ]) ?>
                                            <?= Html::error($bracketColumn, 'title', ['class' => 'field-error']) ?>
                                            
                                        </div>
                                    </div>
                                    <div class="control-sort">
                                        <div class="sort<?= $bracketColumn->active ? '' : ' disabled' ?>">
                                            <div class="sort-arrow up">
                                                <svg class="icon">
                                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                </svg>
                                            </div>
                                            <div class="sort-arrow down">
                                                <svg class="icon">
                                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php elseif($bracketColumn['title'] == 'top'): ?>
                                <div class="control sort-row<?= $bracketColumn->active ? '' : ' disabled' ?>" data-active-checkbox>
                                        <label class="checkbox-label" for="tableColumn<?= $i ?>">
                                            <input class="checkbox-input" type="checkbox" name="<?= $bracketColumn->formName() . '[' . $i . '][active]' ?>"
                                                   id="tableColumn<?= $i ?>" <?= $bracketColumn->active ? 'checked="checked"' : '' ?>>

                                        
                                        <?= Html::hiddenInput($bracketColumn->formName() . '[' . $i . '][id]', $bracketColumn->id, []) ?>
                                        <?= Html::hiddenInput($bracketColumn->formName() . '[' . $i . '][column_type]', 0, []) ?>
                                        <?= Html::hiddenInput($bracketColumn->formName() . '[' . $i . '][order]', $bracketColumn->order, [
                                            'class' => 'dev-append-item-order',
                                        ]) ?>
                                            <?= Html::hiddenInput($bracketColumn->formName() . '[' . $i . '][title]', $bracketColumn->title, []) ?>
                                        </div>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="a-footer a-footer--start">
                <div class="js-table-buttons">
                    <button class="btn js-table-edit" type="submit">save and continue</button>
                    <a class="btn js-popup-close" href="#">cancel</a>
                </div>
                <div class="js-column-del-buttons" style="display: none;">
                    <button class="btn js-column-del-sure" type="submit">i am sure</button>
                    <button class="btn js-column-del-cancel" type="button">cancel</button>
                </div>
            </div>
            <?= Html::endForm(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$css = <<<CSS
    .sort-row .up {
         display: none; 
    }
    .sort-row ~ .sort-row .up {
         display: flex;  
    }
    .sort-row:last-child .sort-arrow.down {
         display: none;  
    } 
CSS;
$this->registerCss($css);



$popupId = '#bracketTableForm';
$js = <<<JS
    $(document).on('click', '.js-table-edit', function(e) {
        let isActive = 0;
        $('[data-active-checkbox] input[type=checkbox]').each(function( index ) {
            if ($(this).prop('checked')) {
                isActive++;
            }
        });
        
        if (isActive < {$isActive}) {
            $('.js-column-del-prompt').show();
            $('.js-column-del-buttons').show();
            $('.js-table-columns').hide();
            $('.js-table-buttons').hide();
            
            e.preventDefault();
            return false;
        }
        
    });
    $(document).on('click', '.js-column-del-cancel', function(e) {
        $('.js-column-del-prompt').hide();
        $('.js-column-del-buttons').hide();
        $('.js-table-columns').show();
        $('.js-table-buttons').show();
    });

    $(document).on('click', '{$popupId} .sort-arrow.up', function() {
        let elUp = $(this).closest('.sort-row'),
            elBefore = elUp.prev();
        
        elBefore.insertAfter(elUp);
        updateColumnsIndices();
    });

    $(document).on('click', '{$popupId} .sort-arrow.down', function() {
        let elDown = $(this).closest('.sort-row'),
            elAfter = elDown.next();
        
        elAfter.insertBefore(elDown);
        updateColumnsIndices();  
    }); 
    
    function updateColumnsIndices() { 
        $('{$popupId} .sort-row').each(function(index) { 
            $(this).find('.control-content input[name]').each(function() {
                $(this).attr('name', $(this).attr('name').replace(/\[(\d+|%i%)\]/g, '[' + index + ']'));
            });
            $(this).find('.dev-append-item-order').val(index);
        });
    }
    
    $(document).on('change', '[data-active-checkbox] input[type=checkbox]', function() {
        let thisCheckbox = $(this),
            rowWrapper = thisCheckbox.closest("[data-active-checkbox]"),
            checkboxText = rowWrapper.find(".checkbox-text");
        
        if ($(this).prop("checked")) {
            if ("undefined" !== typeof(checkboxText.attr("data-text-in"))) {
                checkboxText.text(checkboxText.attr("data-text-in"));
                rowWrapper.removeClass("disabled");
                rowWrapper.find("input").not($(this)).removeClass("disabled");
                rowWrapper.find(".sort").removeClass("disabled"); 
            } 
        } else { 
            if ("undefined" !== typeof(checkboxText.attr("data-text-out"))) {
                checkboxText.text(checkboxText.attr("data-text-out"));
                rowWrapper.addClass("disabled");
                rowWrapper.find("input").not($(this)).addClass("disabled");
                rowWrapper.find(".sort").addClass("disabled"); 
            }
        }
    }); 
    
JS;
$this->registerJs($js);