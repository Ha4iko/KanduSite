<?php

/** @var $this View */
/** @var $model Tournament */

use frontend\models\Tournament;
use common\models\TournamentRule;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

$tournamentRules = $model->tournamentRules;
$isEmpty = false;
if (empty($tournamentRules)) {
    $new = new TournamentRule();
    $new->tournament_id = $model->id;
    $new->order = 0;
    $tournamentRules[0] = $new;

    $isEmpty = true;
}
?>

<div class="popup" id="adminEditRules">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'ruleForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(['/rule/update', 'id' => $model->id], 'post', [
                'data-pjax' => 1,
                'enctype' => 'multipart/form-data',
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
                <div class="popup-title h3"><?= $isEmpty ? 'Add' : 'Edit' ?> Rules</div>
            </div>
            <div class="popup-content">
                <div class="content-block">
                    <div class="add append">
                        <table class="append-template" data-append="rule">
                            <tr class="sort-row append-item" data-append="rule">
                                <td>
                                    <?= Html::hiddenInput('TournamentRule[%i%][id]', null, []) ?>
                                    <?= Html::hiddenInput('TournamentRule[%i%][tournament_id]', $model->id, []) ?>
                                    <?= Html::hiddenInput('TournamentRule[%i%][order]', 0, [
                                        'class' => 'dev-append-item-order',
                                    ]) ?>
                                    <div class="add-cell">
                                        <?= Html::textInput('TournamentRule[%i%][title]', null, [
                                            'placeholder' => 'add title',
                                            'class' => 'field field--md'
                                        ]) ?>
                                    </div>
                                </td>
                                <td class="add-table__lg">
                                    <div class="add-cell">
                                        <?= Html::textarea('TournamentRule[%i%][description]', null, [
                                            'placeholder' => 'add description',
                                            'class' => 'textarea textarea--md'
                                        ]) ?>
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
                                            <a class="clear-btn js-add-clear" href="#" data-append="rule">
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
                                                    <th>title <span>*</span></th>
                                                    <th>description <span>*</span></th>
                                                    <th class="add-table__controls add-table__controls--top"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="sort-rows append-wrap" data-append="rule">
                                                <?php foreach ($tournamentRules as $i => $rule) : ?>
                                                <tr class="sort-row append-item" data-append="rule">
                                                    <td>
                                                        <?= Html::hiddenInput($rule->formName() . '[' . $i . '][id]', $rule->id, []) ?>
                                                        <?= Html::hiddenInput($rule->formName() . '[' . $i . '][tournament_id]', $model->id, []) ?>
                                                        <?= Html::hiddenInput($rule->formName() . '[' . $i . '][order]', $rule->order, [
                                                            'class' => 'dev-append-item-order',
                                                        ]) ?>
                                                        <div class="add-cell <?= $rule->hasErrors('title') ? 'error' : '' ?>">
                                                            <?= Html::textInput($rule->formName() . '[' . $i . '][title]', $rule->title, [
                                                                'placeholder' => 'add title',
                                                                'class' => 'field field--md'
                                                            ]) ?>
                                                            <?= Html::error($rule, 'title', ['class' => 'field-error']) ?>
                                                        </div>
                                                    </td>
                                                    <td class="add-table__lg">
                                                        <div class="add-cell <?= $rule->hasErrors('description') ? 'error' : '' ?>">
                                                            <?= Html::textarea($rule->formName() . '[' . $i . '][description]', $rule->description, [
                                                                'placeholder' => 'add description',
                                                                'class' => 'textarea textarea--md'
                                                            ]) ?>
                                                            <?= Html::error($rule, 'description', ['class' => 'field-error']) ?>
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
                                                                   data-append="rule">
                                                                    <svg class="icon">
                                                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-clear"></use>
                                                                    </svg>
                                                                </a>
                                                            </div>
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
                                <a class="btn btn--md js-add-btn" href="#" data-append="rule">add one</a>
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
$css = <<<CSS
    .append-item:first-child .sort-arrow.up {
         display: none;
    }
    .append-item:last-child .sort-arrow.down {
         display: none;
    }
CSS;
$this->registerCss($css);



$popupId = '#ruleForm';
$js = <<<JS
    $(document).on('click', '{$popupId} .js-add-btn', function() {
        updateRulesIndices();
    });

    $(document).on('click', '{$popupId} .js-add-clear', function() {
        updateRulesIndices();
    });

    $(document).on('click', '{$popupId} .sort-arrow.up', function() {
        const el = $(this).closest('.append-item');
        el.prev().insertAfter(el);
        updateRulesIndices();
    });

    $(document).on('click', '{$popupId} .sort-arrow.down', function() {
        const el = $(this).closest('.append-item');
        el.next().insertBefore(el);
        updateRulesIndices(); 
    }); 
    
    let updateRulesIndices = function() { 
        $('{$popupId} .append-wrap .append-item').each(function(index) {
            $(this).find('input, textarea').each(function() {
                $(this).attr('name', $(this).attr('name').replace(/\[(\d+|%i%)\]/g, '[' + index + ']'));
            });
            $(this).find('.dev-append-item-order').val(index);
        });
    }
JS;
$this->registerJs($js);