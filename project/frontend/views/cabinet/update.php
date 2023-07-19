<?php

/** @var $this View */
/** @var $model TournamentForm */

use frontend\widgets\FileUploadWidget;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\helpers\DataTransformHelper;
use frontend\models\TournamentForm;
use common\models\User;
use common\models\Language;
use common\models\TournamentType;
use yii\helpers\ArrayHelper;

$rolesDropdown = [
    'organizer' => 'Organizer',
    'admin' => 'Admin',
    'root' => 'Superadmin',
];

?>

<div class="popup" id="userEditForm">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'userForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(
                    $model->isNewRecord ? 
                        ['/cabinet/create'] :
                        ['/cabinet/update', 'id' => $model->id],
                    'post', [
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
                <div class="popup-title h3">
                    <?= $model->isNewRecord ? 'Add' : 'Edit' ?> user <span>/ nickname</span>
                </div>
            </div>
            <div class="popup-content">
                <div class="content-block">
                    <div class="controls">

                        <div class="control">
                            <div class="control-side">
                                <div class="prop">
                                    <?= $model->getAttributeLabel('user_name') ?> <span>*</span>
                                </div>
                            </div>
                            <div class="control-content">
                                <div class="control-fields">
                                    <div class="control-field <?= $model->hasErrors('user_name') ? 'error' : '' ?>">
                                        <?= Html::activeTextInput($model, 'user_name', [
                                            'placeholder' => 'enter nickname',
                                            'class' => 'field'
                                        ]) ?>
                                        <?= Html::error($model, 'user_name', ['class' => 'field-error']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="control">
                            <div class="control-side">
                                <div class="prop">
                                    <?= $model->getAttributeLabel('user_email') ?> <span>*</span>
                                </div>
                            </div>
                            <div class="control-content">
                                <div class="control-fields">
                                    <div class="control-field <?= $model->hasErrors('user_email') ? 'error' : '' ?>">
                                        <?= Html::activeTextInput($model, 'user_email', [
                                            'placeholder' => 'enter email',
                                            'class' => 'field'
                                        ]) ?>
                                        <?= Html::error($model, 'user_email', ['class' => 'field-error']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($model->isNewRecord) : ?>
                        <div class="control">
                            <div class="control-side">
                                <div class="prop">
                                    <?= $model->getAttributeLabel('user_password') ?> <span>*</span>
                                </div>
                            </div>
                            <div class="control-content">
                                <div class="control-fields">
                                    <div class="control-field <?= $model->hasErrors('user_password') ? 'error' : '' ?>">
                                        <?= Html::activePasswordInput($model, 'user_password', [
                                            'placeholder' => 'enter password',
                                            'class' => 'field'
                                        ]) ?>
                                        <?= Html::error($model, 'user_password', ['class' => 'field-error']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="control">
                            <div class="control-side">
                                <div class="prop">
                                    <?= $model->getAttributeLabel('user_role') ?> <span>*</span>
                                </div>
                            </div>
                            <div class="control-content">
                                <div class="control-fields">
                                    <div class="control-field <?= $model->hasErrors('user_role') ? 'error' : '' ?>">
                                        <div class="select">
                                            <div class="select-btn">
                                                <?= Html::activeDropDownList($model, 'user_role',
                                                    $rolesDropdown,
                                                    [
                                                        'data-placeholder' => 'choose role',
                                                        'data-style' => '2',
                                                        'class' => 'js-select'
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
                                        <?= Html::error($model, 'user_role', ['class' => 'field-error']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="a-footer">
                <button class="btn" type="submit">save and continue</button>
                <a class="btn js-popup-close" href="#">cancel</a>
            </div>
            <?= Html::endForm(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

