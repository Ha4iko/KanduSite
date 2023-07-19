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
use frontend\models\TournamentType;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$timeDropdown = [];
for ($i = 1; $i < 25; $i++) {
    $timeDropdown[sprintf("%02d", $i) . ':00:00'] = sprintf("%02d", $i) . ':00';
}

FileUploadWidget::widget([
    'inputSelector' => '#bg_image_value',
    'previewSelector' => '#bg_image_preview',
    'loadingSelector' => '#tournamentEditForm'
]);

$canChangeType = true;
if ($model->tournamentToPlayer || $model->tournamentToTeam || $model->brackets) {
    $canChangeType = false;
}

$pendingStatus = $model->status === TournamentForm::STATUS_PENDING || $model->isNewRecord;
?>

<div class="popup" id="tournamentEditForm">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'tournamentMainDataForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(
                    $model->isNewRecord ? 
                        ['/tournament/create'] : 
                        ['/tournament/update', 'id' => $model->id], 
                    'post', [
                'data-pjax' => 1,
                'enctype' => 'multipart/form-data',
            ]); ?>

            <?php /*if ($model->id && $model->status == TournamentForm::STATUS_IN_PROGRESS) : ?>

                <div class="popup-head">
                    <div class="popup-close js-popup-close">
                        <a class="link-back" href="#">
                            <svg class="icon">
                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>close</a>
                    </div>
                    <div class="popup-title h3 primary">Forbidden action</div>
                </div>
                <input id="tournamentId" type="hidden" name="tournamentId">
                <div class="popup-content">
                    <div class="content-block">
                        <p class="secondary">You cannot change main info while tournament in progress</p>
                    </div>
                </div>
                <div class="a-footer a-footer--start">
                    <a class="btn js-popup-close" href="#">cancel</a>
                </div>

            <?php else:*/ ?>

                <input class="js-popup-redirect" type="hidden" name="ajaxPopupRedirect" value="">
                <div class="popup-head">
                    <div class="popup-close js-popup-close"><a class="link-back" href="#">
                            <svg class="icon">
                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>close</a></div>
                    <div class="popup-title h3"><?= $model->isNewRecord ? 'Add' : 'Edit' ?> main info <span>/ tournament</span></div>
                </div>
                <div class="popup-content">
                    <div class="content-block">
                        <div class="controls">

                            <?php if (Yii::$app->user->can('admin') || Yii::$app->user->can('root')) : ?>
                            <div class="control">
                                <div class="control-side">
                                    <div class="prop">Organizer <span>*</span></div>
                                </div>
                                <div class="control-content">
                                    <div class="control-fields">
                                        <div class="control-field">
                                            <div class="select">
                                                <div class="select-btn">
                                                    <?= Html::activeDropDownList($model, 'organizer_id',
                                                        DataTransformHelper::getList(User::class, 'username'),
                                                        [
                                                            'data-placeholder' => 'choose',
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
                                            <?= Html::error($model, 'organizer_id', ['class' => 'field-error']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($pendingStatus) : ?>
                            <div class="control">
                                <div class="control-side">
                                    <div class="prop">The name of the tournament <span>*</span></div>
                                </div>
                                <div class="control-content">
                                    <div class="control-fields">
                                        <div class="control-field <?= $model->hasErrors('title') ? 'error' : '' ?>">
                                            <?= Html::activeTextInput($model, 'title', [
                                                'placeholder' => 'tournament',
                                                'class' => 'field'
                                            ]) ?>
                                            <?= Html::error($model, 'title', ['class' => 'field-error']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($canChangeType && $pendingStatus) : ?>
                            <div class="control">
                                <div class="control-side">
                                    <div class="prop">tournament type <span>*</span></div>
                                </div>
                                <div class="control-content">
                                    <div class="control-fields">
                                        <div class="control-field <?= $model->hasErrors('type_id') ? 'error' : '' ?>">
                                            <div class="select">
                                                <div class="select-btn">
                                                    <?= Html::activeDropDownList($model, 'type_id',
                                                        TournamentType::getTypeNamesKeyId(),
                                                        [
                                                            'data-placeholder' => 'choose',
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
                                            <?= Html::error($model, 'type_id', ['class' => 'field-error']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($pendingStatus) : ?>
                            <div class="control">
                                <div class="control-side">
                                    <div class="prop">language <span>*</span></div>
                                </div>
                                <div class="control-content">
                                    <div class="control-fields">
                                        <div class="control-field <?= $model->hasErrors('language_id') ? 'error' : '' ?>">
                                            <div class="select">
                                                <div class="select-btn">
                                                    <?= Html::activeDropDownList($model, 'language_id',
                                                        DataTransformHelper::getList(Language::class, 'name'),
                                                        [
                                                            'data-placeholder' => 'choose',
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
                                            <?= Html::error($model, 'language_id', ['class' => 'field-error']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($pendingStatus) : ?>
                            <div class="control">
                                <div class="control-side">
                                    <div class="prop">timezone <span>*</span></div>
                                </div>
                                <div class="control-content">
                                    <div class="control-fields">
                                        <div class="control-field <?= $model->hasErrors('time_zone') ? 'error' : '' ?>">
                                            <div class="select">
                                                <div class="select-btn">
                                                    <?= Html::activeDropDownList($model, 'time_zone',
                                                        Yii::$app->params['timeZones'],
                                                        [
                                                            'data-placeholder' => 'choose time zone',
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="control">
                                <div class="control-side">
                                    <div class="prop">Prize Pool</div>
                                </div>
                                <div class="control-content">
                                    <div class="control-fields">
                                        <div class="control-field <?= $model->hasErrors('pool_custom') ? 'error' : '' ?>">
                                            <?= Html::activeTextInput($model, 'pool_custom', [
                                                'placeholder' => 'prize pool',
                                                'class' => 'field',
                                            ]) ?>
                                            <?= Html::error($model, 'pool_custom', ['class' => 'field-error']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="content-block">
                        <div class="controls">
                            <div class="control">
                                <div class="control-side">
                                    <div class="prop">Tournament start date and time <span>*</span></div>
                                </div>
                                <div class="control-content">
                                    <div class="control-fields">
                                        <div class="control-field control-field--sm <?= $model->hasErrors('date') ? 'error' : '' ?>">
                                            <div class="datepicker-el js-datepicker datepicker-el--style-2 <?= $model->dateFormatted ? 'selected' : '' ?>">
                                                <div class="datepicker-el__btn field">
                                                    <div class="datepicker-el__btn-text prop"><?= $model->dateFormatted ?: 'choose' ?></div>
                                                    <div class="datepicker-el__btn-icon">
                                                        <svg class="icon">
                                                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="datepicker-el__drop">
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
                                                    <div class="datepicker-el__inner">
                                                        <input class="datepicker-el__input" name="<?= $model->formName() ?>[dateFormatted]" value="<?= $model->dateFormatted ?>" type="text" readonly="true">
                                                    </div>
                                                </div>
                                            </div>
                                            <?= Html::error($model, 'date', ['class' => 'field-error']) ?>
                                        </div>
                                        <div class="control-field control-field--sm <?= $model->hasErrors('time') ? 'error' : '' ?>">
                                            <div class="select">
                                                <div class="select-btn">
                                                    <?= Html::activeDropDownList($model, 'time',
                                                        ArrayHelper::merge([null => ''], $timeDropdown),
                                                        [
                                                            'data-placeholder' => 'choose',
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
                                            <?= Html::error($model, 'time', ['class' => 'field-error']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="control">
                                <div class="control-side">
                                    <div class="prop">Tournament finale date and time (last match)</div>
                                </div>
                                <div class="control-content">
                                    <div class="control-fields">
                                        <div class="control-field control-field--sm <?= $model->hasErrors('dateFinal') ? 'error' : '' ?>">
                                            <div class="datepicker-el js-datepicker datepicker-el--style-2 <?= $model->dateFinalFormatted ? 'selected' : '' ?>">
                                                <div class="datepicker-el__btn field">
                                                    <div class="datepicker-el__btn-text prop"><?= $model->dateFinalFormatted ?: 'choose' ?></div>
                                                    <div class="datepicker-el__btn-icon">
                                                        <svg class="icon">
                                                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="datepicker-el__drop">
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
                                                    <div class="datepicker-el__inner">
                                                        <input class="datepicker-el__input" name="<?= $model->formName() ?>[dateFinalFormatted]" value="<?= $model->dateFinalFormatted ?>"  type="text" readonly="true">
                                                    </div>
                                                </div>
                                            </div>
                                            <?= Html::error($model, 'dateFinal', ['class' => 'field-error']) ?>
                                        </div>
                                        <div class="control-field control-field--sm <?= $model->hasErrors('time_final') ? 'error' : '' ?>">
                                            <div class="select">
                                                <div class="select-btn">
                                                    <?= Html::activeDropDownList($model, 'time_final',
                                                        ArrayHelper::merge([null => ''], $timeDropdown),
                                                        [
                                                            'data-placeholder' => 'choose',
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
                                            <?= Html::error($model, 'time_final', ['class' => 'field-error']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($pendingStatus) : ?>
                    <div class="content-block">
                        <div class="controls">
                            <div class="control control--top">
                                <div class="control-side">
                                    <div class="prop">tournament cover <span>*</span></div>
                                </div>
                                <div class="control-content">
                                    <div class="control-fields">
                                        <div class="control-field <?= $model->hasErrors('bg_image') ? 'error' : '' ?>">
                                            <div class="control-file">
                                                <label class="control-file__btn js-upload" for="tournamentBg">
                                                    <div class="btn btn--md" data-text-in="add cover" data-text-out="change-cover">
                                                        <?= $model->bg_image ? 'Edit' : 'Add' ?> cover
                                                    </div>
                                                    <input class="control-file__field" type="file" id="tournamentBg">
                                                    <input id="bg_image_value" class="control-file__field" type="hidden" name="<?= $model->formName() ?>[bg_image]" value="<?= $model->bg_image ?>">
                                                </label>
                                            </div>
                                            <?= Html::error($model, 'bg_image', ['class' => 'field-error', 'style' => 'margin-left: 0;']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-main">
                                    <div class="control-file__bg  <?= $model->bg_image ? 'active' : '' ?>">
                                        <img id="bg_image_preview" style="object-position: center center !important;" src="<?= $model->getThumbnail('bg_image', 1024) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="a-footer">
                    <button class="btn" type="submit">save and continue</button>
                    <a class="btn js-popup-close" href="#">cancel</a>
                </div>

            <?php //endif; ?>

            <?= Html::endForm(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

