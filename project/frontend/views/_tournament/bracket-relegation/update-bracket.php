<?php

use frontend\models\BracketRelegationForm;
use frontend\models\Tournament;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var $this View */
/** @var $model BracketRelegationForm */

// echo '<pre>';
// var_dump($model->attributes);
// echo '</pre>';
// exit;

//$bracketColumns = $model->getBracketColumnsAppended();
//$teamMode = ($type = $model->tournament->type) ? boolval($type->team_mode) : false;
?>
<div class="popup" id="adminCreateRelegation">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'bracketRelegationForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(['/bracket-relegation/update-bracket', 'id' => $model->tournament_id, 'bracketId' => $model->id], 'post', [
                'data-pjax' => 1,
                'enctype' => 'multipart/form-data',
            ]); ?>
            <?= Html::activeHiddenInput($model, 'id') ?>

            <div class="popup-head">
                <div class="popup-close js-popup-close">
                    <a class="link-back" href="#">
                        <svg class="icon">
                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                        </svg>close
                    </a>
                </div>
                <div class="popup-title h3"><?= $model->isNewRecord ? 'Add' : 'Edit' ?> bracket <span>/ relegation</span></div>
            </div>

            <div class="popup-content">

                <div class="content-block">
                    <div class="control">
                        <div class="control-side">
                            <div class="prop">Name of Bracket <span>*</span></div>
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

                <?php if ($model->editable) : ?>
                <div class="content-block">
                    <div class="controls">
                        <div class="control">
                            <div class="control-side">
                                <div class="prop">Number of participants</div>
                            </div>
                            <div class="control-content">
                                <div class="control-fields">
                                    <div class="control-field control-field--sm <?= $model->hasErrors('participants') ? 'error' : '' ?>">
                                        <div class="select">
                                            <div class="select-btn">
                                                <?= Html::activeDropDownList($model, 'participants',
                                                    [
                                                        4 => 4,
                                                        8 => 8,
                                                        16 => 16,
                                                        32 => 32,
                                                        64 => 64,
                                                    ],
                                                    [
                                                        'data-placeholder' => 'choose',
                                                        'data-style' => '2',
                                                        'class' => 'js-select',
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
                                        <?= Html::error($model, 'participants', ['class' => 'field-error']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="control">
                            <div class="control-side">
                                <div class="prop">Best of</div>
                            </div>
                            <div class="control-content">
                                <div class="control-fields">
                                    <div class="control-field control-field--sm <?= $model->hasErrors('best_of') ? 'error' : '' ?>">
                                        <div class="select">
                                            <div class="select-btn">
                                                <?= Html::activeDropDownList($model, 'best_of',
                                                    [
                                                        1 => 1,
                                                        3 => 3,
                                                        5 => 5,
                                                    ],
                                                    [
                                                        'data-placeholder' => 'choose',
                                                        'data-style' => '2',
                                                        'class' => 'js-select',
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
                                        <?= Html::error($model, 'best_of', ['class' => 'field-error']) ?>
                                    </div>
                                    <div class="control-field__hint">
                                        <div class="text--sm">Choose the number of rounds 1, 3 or 5 according to the results of which the winner passes on</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-block">
                    <div class="controls">
                        <div class="control control--top">
                            <div class="control-side">
                            </div>
                            <div class="control-content">
                                <div class="control-fields">
                                    <div class="control-field">
                                        <div class="checkbox">
                                            <label class="checkbox-label" for="battleForThird">
                                                <?= Html::activeCheckbox($model, 'third_place', [
                                                    'class' => 'checkbox-input',
                                                    'id' => 'battleForThird',
                                                    'label' => false
                                                ]) ?>
                                                <div class="checkbox-content">
                                                    <div class="checkbox-style"></div>
                                                    <div class="checkbox-text h6">Battle for the 3rd place</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-block">
                    <div class="controls">
                        <div class="control control--top">
                            <div class="control-side">
                                <div class="prop">elimination type</div>
                            </div>
                            <div class="control-content">
                                <div class="control-fields">
                                    <div class="control-field">
                                        <div class="radios">
                                            <div class="radio radio--sm">
                                                <label class="radio-label" for="eliminationType1">
                                                    <input class="radio-input" type="radio"
                                                        name="<?= $model->formName() . '[second_defeat]' ?>" id="eliminationType1" value="0"
                                                           <?= !$model->second_defeat ? 'checked="checked"' : '' ?>>
                                                    <div class="radio-content">
                                                        <div class="radio-style"></div>
                                                        <div class="radio-text h6">Elimination at the first defeat</div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="radio radio--sm">
                                                <label class="radio-label" for="eliminationType2">
                                                    <input class="radio-input" type="radio"
                                                        name="<?= $model->formName() . '[second_defeat]' ?>" id="eliminationType2" value="1"
                                                        <?= $model->second_defeat ? 'checked="checked"' : '' ?>>
                                                    <div class="radio-content">
                                                        <div class="radio-style"></div>
                                                        <div class="radio-text h6">Elimination after the second defeat</div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="a-footer a-footer--start">
                <button class="btn" type="submit">save and continue</button>
                <a class="btn js-popup-close" data-pjax="0" href="#">cancel</a>
            </div>
            <?= Html::endForm(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
