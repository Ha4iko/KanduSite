<?php

/** @var $this View */
/** @var $model \frontend\models\ScheduleForm */

use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;
use frontend\models\TournamentSchedule;

$timeDropdown = [];
for ($i = 1; $i < 25; $i++) {
    $timeDropdown[sprintf("%02d", $i) . ':00:00'] = sprintf("%02d", $i) . ':00';
}

$tournamentSchedules = $model->tournamentSchedules;
$isEmpty = false;
if (empty($tournamentSchedules)) {
    $new = new TournamentSchedule();
    $new->tournament_id = $model->id;
    $new->order = 0;
    $tournamentSchedules[0] = $new;

    $isEmpty = true;
}
?>

<div class="popup" id="adminAddSchedule">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'scheduleForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(['/schedule/update', 'id' => $model->id], 'post', [
                'data-pjax' => 1,
                'enctype' => 'multipart/form-data',
            ]); ?>
            <?= Html::hiddenInput($model->formName() . '[id]', $model->id, []) ?>
            <div class="popup-head">
                <div class="popup-close js-popup-close"><a class="link-back" href="#">
                        <svg class="icon">
                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                        </svg>close</a></div>
                <div class="popup-title h3"><?= $isEmpty ? 'Add' : 'Edit' ?> schedule</div>
            </div>
            <div class="popup-content">
                <div class="content-block">
                    <div class="radios">
                        <div class="radio radio--sm">
                            <label class="radio-label" for="addSchedule1">
                                <input class="radio-input" type="radio" name="<?= $model->formName() ?>[mark_type]" id="addSchedule1" <?= !$model->mark_type ? 'checked="checked"' : '' ?> value="0">
                                <div class="radio-content">
                                    <div class="radio-style"></div>
                                    <div class="radio-text h6">all matches at same times</div>
                                </div>
                            </label>
                        </div>
                        <div class="radio radio--sm">
                            <label class="radio-label" for="addSchedule2">
                                <input class="radio-input" type="radio" name="<?= $model->formName() ?>[mark_type]" id="addSchedule2" <?= $model->mark_type ? 'checked="checked"' : '' ?> value="1">
                                <div class="radio-content">
                                    <div class="radio-style"></div>
                                    <div class="radio-text h6">the tournament is split into rounds at different times</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="content-block">
                    <div class="add append">
                        <table class="append-template" data-append="schedule">
                            <tr class="sort-row append-item" data-append="schedule">
                                <td class="add-table__lg">
                                    <?= Html::hiddenInput('TournamentSchedule[%i%][id]', null, []) ?>
                                    <?= Html::hiddenInput('TournamentSchedule[%i%][tournament_id]', $model->id, []) ?>
                                    <?= Html::hiddenInput('TournamentSchedule[%i%][order]', 0, [
                                        'class' => 'dev-append-item-order',
                                    ]) ?>
                                    <div class="add-cell">
                                        <?= Html::textInput('TournamentSchedule[%i%][title]', null, [
                                            'placeholder' => 'add title',
                                            'class' => 'field field--md',
                                        ]) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="add-cell">
                                        <div class="datepicker-el js-datepicker datepicker-el--style-2 datepicker-el--md">
                                            <div class="datepicker-el__btn">
                                                <div class="datepicker-el__btn-text prop">choose</div>
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
                                                    <?= Html::textInput('TournamentSchedule[%i%][dateFormatted]', null, [
                                                        //'placeholder' => 'enter nickname',
                                                        'readonly' => 'true',
                                                        'class' => 'datepicker-el__input'
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="add-cell">
                                        <div class="select select--md">
                                            <div class="select-btn">
                                                <?= Html::dropDownList('TournamentSchedule[%i%][time]', null,
                                                    ArrayHelper::merge([null => ''], $timeDropdown),
                                                    [
                                                        'data-placeholder' => 'choose time',
                                                        'data-drop' => 'select--md',
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
                                    </div>
                                </td>
                                <td class="add-table__controls add-table__controls--top">
                                    <div class="table-controls">
                                        <div class="sort">
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
                                        <div class="clear">
                                            <a class="clear-btn js-add-clear" href="#" data-append="schedule">
                                                <svg class="icon">
                                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-clear"></use>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="add-table">
                            <div class="table table--static table--controls">
                                <div class="table-content">
                                    <div class="table-inner">
                                        <table>
                                            <thead>
                                            <tr>
                                                <th class="add-table__lg">title <span>*</span></th>
                                                <th>date <span>*</span></th>
                                                <th>time <span>*</span></th>
                                                <th class="add-table__controls add-table__controls--top"></th>
                                            </tr>
                                            </thead>
                                            <tbody class="sort-rows append-wrap" data-append="schedule">
                                            <?php foreach ($tournamentSchedules as $i => $schedule) : ?>
                                                <tr class="sort-row append-item" data-append="schedule">
                                                <?= Html::hiddenInput($schedule->formName() . "[$i][id]", $schedule->id, []) ?>
                                                <?= Html::hiddenInput($schedule->formName() . "[$i][tournament_id]", $model->id, []) ?>
                                                <?= Html::hiddenInput($schedule->formName() . "[$i][order]", $schedule->order, [
                                                    'class' => 'dev-append-item-order',
                                                ]) ?>
                                                <td class="add-table__lg">
                                                    <div class="add-cell <?= $schedule->hasErrors('title') ? 'error' : '' ?>">
                                                        <!--input class="field field--md" type="text" placeholder="add title"-->
                                                        <?= Html::textInput($schedule->formName() . "[$i][title]", $schedule->title, [
                                                            'placeholder' => 'add title',
                                                            'class' => 'field field--md'
                                                        ]) ?>
                                                        <?= Html::error($schedule, 'title', ['class' => 'field-error']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="add-cell <?= $schedule->hasErrors('dateFormatted') ? 'error' : '' ?>">
                                                        <div class="datepicker-el js-datepicker datepicker-el--style-2 datepicker-el--md <?= $schedule->dateFormatted ? 'selected' : '' ?>">
                                                            <div class="datepicker-el__btn">
                                                                <div class="datepicker-el__btn-text prop"><?= $schedule->dateFormatted ?: 'choose' ?></div>
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
                                                                    <!--input class="datepicker-el__input" name="" type="text" readonly="true"-->
                                                                    <?= Html::textInput($schedule->formName() . "[$i][dateFormatted]", $schedule->dateFormatted, [
                                                                        //'placeholder' => 'enter nickname',
                                                                        'readonly' => 'true',
                                                                        'class' => 'datepicker-el__input'
                                                                    ]) ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?= Html::error($schedule, 'dateFormatted', ['class' => 'field-error']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="add-cell <?= $schedule->hasErrors('time') ? 'error' : '' ?>">
                                                        <div class="select select--md">
                                                            <div class="select-btn">
                                                                <?= Html::dropDownList($schedule->formName() . "[$i][time]", $schedule->time,
                                                                    ArrayHelper::merge([null => ''], $timeDropdown),
                                                                    [
                                                                        'data-placeholder' => 'choose time',
                                                                        'data-drop' => 'select--md',
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
                                                        <?= Html::error($schedule, 'time', ['class' => 'field-error']) ?>
                                                    </div>
                                                </td>
                                                    <td class="add-table__controls add-table__controls--top">
                                                        <div class="table-controls">
                                                            <div class="sort">
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
                                                            <div class="clear">
                                                                <a class="clear-btn js-add-clear" href="#"
                                                                   data-append="schedule">
                                                                    <svg class="icon">
                                                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-clear"></use>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                            </tr>
                                            <?php
                                                if (!$model->mark_type) break;
                                            endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="add-controls">
                            <div class="add-controls__btn">
                                <a class="btn btn--md js-add-btn js-case-visible" href="#"
                                   style="<?= !$model->mark_type ? 'display: none;' : '' ?>" data-append="schedule">add one more</a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (Yii::$app->user->can('admin') || Yii::$app->user->can('root')) : ?>
                <div class="content-block">
                    <div class="toggler" data-checkbox>
                        <div class="toggler-checkbox js-on-home">
                            <div class="checkbox checkbox--toggler">
                                <label class="checkbox-label" for="scheduleCheckbox1">
                                    <input class="checkbox-input" name="<?= $model->formName() ?>[mark_page]" type="checkbox" id="scheduleCheckbox1" <?= $model->show_on_main_page ? 'checked="checked"' : '' ?>>
                                    <div class="checkbox-content">
                                        <div class="checkbox-style"></div>
                                        <div class="checkbox-text h6">Place on main page</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="toggler-content js-on-home-block <?= $model->show_on_main_page ? '' : 'waiting' ?>">
                            <div class="radios">
                                <div class="radio radio--sm">
                                    <label class="radio-label" for="scheduleTournaments1">
                                        <input class="radio-input" type="radio" name="<?= $model->formName() ?>[mark_primary]" id="scheduleTournaments1" <?= $model->mark_primary ? 'checked="checked"' : '' ?> value="1">
                                        <div class="radio-content">
                                            <div class="radio-style"></div>
                                            <div class="radio-text h6">Primary tournaments</div>
                                        </div>
                                    </label>
                                </div>
                                <div class="radio radio--sm">
                                    <label class="radio-label" for="scheduleTournaments2">
                                        <input class="radio-input" type="radio" name="<?= $model->formName() ?>[mark_primary]" id="scheduleTournaments2" <?= !$model->mark_primary ? 'checked="checked"' : '' ?> value="0">
                                        <div class="radio-content">
                                            <div class="radio-style"></div>
                                            <div class="radio-text h6">non-Primary tournaments</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
            <div class="a-footer a-footer--start">
                <button class="btn" type="submit">save and continue</button>
                <a class="btn js-popup-close" href="#">cancel</a>
            </div>
            <?= Html::endForm(); ?>
            <div id="disabledItems" style="display: none;"></div>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$css = <<<CSS
    .append-item:first-child .sort-arrow.up {
         display: none;
    }
    .append-item:last-child .sort-arrow.down {
         display: none;
    }
CSS;
$this->registerCss($css);



$popupId = '#scheduleForm';
$js = <<<JS
    $(document).on('change', 'input[name="ScheduleForm[mark_type]"]', function(e) {
        let selectedType = $('input[name="ScheduleForm[mark_type]"]:checked').attr('id');
        if (selectedType === 'addSchedule1') {
            $('.add-table .append-wrap tr:not(:first)').appendTo('#disabledItems');
            $('.js-case-visible').hide();
        } else {
            $('#disabledItems tr').appendTo('.add-table .append-wrap');
            $('.js-case-visible').show();
        }
    });

    $(document).on('click', '{$popupId} .js-add-btn', function() {
        updateScheduleIndices();
        datepickerReInit();
    });

    $(document).on('click', '{$popupId} .js-add-clear', function() {
        updateScheduleIndices();
    });

    $(document).on('click', '{$popupId} .sort-arrow.up', function() {
        const el = $(this).closest('.append-item');
        el.prev().insertAfter(el);
        updateScheduleIndices();
    });

    $(document).on('click', '{$popupId} .sort-arrow.down', function() {
        const el = $(this).closest('.append-item');
        el.next().insertBefore(el);
        updateScheduleIndices(); 
    }); 
    
    $(document).on('change', '.js-on-home input', function() {
        $('.js-on-home-block').toggleClass('waiting'); 
    }); 
    
    function updateScheduleIndices() { 
        $('{$popupId} .append-wrap .append-item').each(function(index) {
            $(this).find('input, textarea, select').each(function() {
                $(this).attr('name', $(this).attr('name').replace(/\[(\d+|%i%)\]/g, '[' + index + ']'));
            });
            $(this).find('.dev-append-item-order').val(index);
        });
    }
JS;
$this->registerJs($js);