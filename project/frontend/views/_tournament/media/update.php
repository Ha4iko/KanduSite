<?php

/** @var $this View */
/** @var $model Tournament */

use frontend\models\Tournament;
use common\models\TournamentMedia;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

$tournamentMedias = $model->tournamentMedias;
if (empty($tournamentMedias)) {
    $new = new TournamentMedia();
    $new->tournament_id = $model->id;
    $tournamentMedias[0] = $new;
}
?>

<div class="popup" id="adminAddMedia">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'mediaForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(['/tournament-media/update', 'id' => $model->id], 'post', [
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
                <div class="popup-title h3"><?= !count($model->tournamentMediaNotEmpty) ? 'Add' : 'Edit' ?> Media</div>
            </div>
            <div class="popup-content">
                <div class="content-block">
                    <div class="add append">
                        <table class="append-template" data-append="media">
                            <tr class="append-item" data-append="media">
                                <td>
                                    <?= Html::hiddenInput('TournamentMedia[%i%][id]', null, []) ?>
                                    <?= Html::hiddenInput('TournamentMedia[%i%][tournament_id]', $model->id, []) ?>
                                    <div class="add-cell">
                                        <?= Html::textInput('TournamentMedia[%i%][content]', '', [
                                            'placeholder' => 'enter',
                                            'class' => 'field field--md'
                                        ]) ?>
                                    </div>
                                </td>
                                <td class="add-table__clear">
                                    <div class="clear">
                                        <a class="clear-btn js-add-clear" href="#" data-append="media">
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
                                            <thead>
                                            <tr>
                                                <th>Youtube video link <span>*</span></th>
                                                <th class="add-table__clear"></th>
                                            </tr>
                                            </thead>
                                            <tbody class="append-wrap" data-append="media">
                                            <?php foreach ($tournamentMedias as $i => $media) : ?>
                                                <tr class="append-item" data-append="media">
                                                    <td>
                                                        <?= Html::hiddenInput($media->formName() . '[' . $i . '][id]', $media->id, []) ?>
                                                        <?= Html::hiddenInput($media->formName() . '[' . $i . '][tournament_id]', $model->id, []) ?>
                                                        <div class="add-cell <?= $media->hasErrors('content') ? 'error' : '' ?>">
                                                            <?= Html::textInput($media->formName() . '[' . $i . '][content]', $media->content, [
                                                                'placeholder' => 'enter',
                                                                'class' => 'field field--md',
                                                                'style' => 'text-transform: none !important',
                                                            ]) ?>
                                                            <?= Html::error($media, 'content', ['class' => 'field-error', 'style' => 'color: #DF0D14; display:block;']) ?>
                                                        </div>
                                                    </td>
                                                    <td class="add-table__clear">
                                                        <div class="clear">
                                                            <a class="clear-btn js-add-clear" href="#" data-append="media">
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
                                <a class="btn btn--md js-add-btn" href="#" data-append="media">
                                    add one more
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
$popupId = '#mediaForm';
$js = <<<JS
    $(document).on('click', '{$popupId} .js-add-btn', function() {
        updateMediaIndices();
    });

    $(document).on('click', '{$popupId} .js-add-clear', function() {
        updateMediaIndices();
    });

    let updateMediaIndices = function() { 
        $('{$popupId} .append-wrap .append-item').each(function(index) {
            $(this).find('input, textarea').each(function() {
                $(this).attr('name', $(this).attr('name').replace(/\[(\d+|%i%)\]/g, '[' + index + ']'));
            });
            $(this).find('.dev-append-item-order').val(index);
        });
    }
JS;
$this->registerJs($js);