<?php

use frontend\models\BracketGroupForm;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var $this View */
/** @var $model BracketGroupForm */

?>
<div class="popup" id="adminAddBracketGroup">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'bracketGroupForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(['/bracket-group/update-bracket', 'id' => $model->tournament_id, 'bracketId' => $model->id], 'post', [
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
                <div class="popup-title h3"><?= $model->isNewRecord ? 'Add' : 'Edit' ?> bracket <span>/ round (group)</span></div>
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

                    <?php if ($model->editable) : ?>
                    <div class="control">
                        <div class="control-side">
                            <div class="prop">Number of groups</div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field control-field--sm <?= $model->hasErrors('group_count') ? 'error' : '' ?>">
                                    <div class="select">
                                        <div class="select-btn">
                                            <?= Html::activeDropDownList($model, 'group_count',
                                                [
                                                    1 => 1,
                                                    2 => 2,
                                                    3 => 3,
                                                    4 => 4,
                                                    5 => 5,
                                                    6 => 6,
                                                    7 => 7,
                                                    8 => 8,
                                                    9 => 9,
                                                    10 => 10,
                                                    11 => 11,
                                                    12 => 12,
                                                    13 => 13,
                                                    14 => 14,
                                                    15 => 15,
                                                    16 => 16,
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
                                    <?= Html::error($model, 'group_count', ['class' => 'field-error']) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="control">
                        <div class="control-side">
                            <div class="prop">Number of players in the group</div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field control-field--sm <?= $model->hasErrors('participants') ? 'error' : '' ?>">
                                    <div class="select">
                                        <div class="select-btn">
                                            <?= Html::activeDropDownList($model, 'participants',
                                                [
                                                    3 => 3,
                                                    4 => 4,
                                                    5 => 5,
                                                    6 => 6,
                                                    7 => 7,
                                                    8 => 8,
                                                    9 => 9,
                                                    10 => 10,
                                                    11 => 11,
                                                    12 => 12,
                                                    13 => 13,
                                                    14 => 14,
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
                                                    2 => 2,
                                                    3 => 3,
                                                    4 => 4,
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
                                    <div class="text--sm">There may be an even and an odd number, If it is even, then there may be a draw</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

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
