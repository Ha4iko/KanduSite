<?php

use frontend\models\MediaForm;
use frontend\widgets\FileUploadWidget;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;
use frontend\assets\QuillAsset;

/** @var $this View */
/** @var $model MediaForm */

QuillAsset::register($this);

FileUploadWidget::widget([
    'inputSelector' => '#bg_image_value',
    'previewSelector' => '#bg_image_preview',
    'loadingSelector' => '#mediaEditForm'
]);

echo ($modelErrors = $model->getErrorSummary(true))
	? Html::tag('div', implode('<br>', $modelErrors), ['class' => 'content-block', 'style' => 'color: #DF0D14;'])
	: '';

?>

<div class="popup" id="mediaEditForm">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'mediaForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(
                    ['/site-media/update', 'id' => $model->id],
                    'post', [
                'data-pjax' => 1,
                'enctype' => 'multipart/form-data',
            ]); ?>

            <?= Html::activeHiddenInput($model, 'id') ?>
            <!--input class="js-popup-redirect" type="hidden" name="ajaxPopupRedirect" value=""-->

            <div class="popup-head">
                <div class="popup-close js-popup-close">
                    <a class="link-back" href="#">
                        <svg class="icon">
                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                        </svg>close
                    </a>
                </div>
                <div class="popup-title h3">
                    <?= $model->isNewRecord ? 'Add' : 'Edit' ?> media <span>/ site</span>
                </div>
            </div>

            <div class="popup-content">
                <div class="content-block">
                    <div class="controls">
                        <div class="control">
                            <div class="control-side">
                                <div class="prop">Title <span>*</span></div>
                            </div>
                            <div class="control-content">
                                <div class="control-fields">
                                    <div class="control-field <?= $model->hasErrors('title') ? 'error' : '' ?>">
                                        <?= Html::activeTextInput($model, 'title', [
                                            'placeholder' => 'title',
                                            'class' => 'field'
                                        ]) ?>
                                        <?= Html::error($model, 'title', ['class' => 'field-error']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="control">
                            <div class="control-side">
                                <div class="prop">Content <span>*</span></div>
                            </div>
                            <div class="control-content">
                                <div class="control-fields">
                                    <div class="control-field <?= $model->hasErrors('content') ? 'error' : '' ?>">
                                        <div class="standalone-container">
                                            <div id="toolbar-container">
                                                <span class="ql-formats">
                                                    <button class="ql-header" value="5"></button>
                                                </span>
                                                <span class="ql-formats">
                                                    <button class="ql-link"></button>
                                                    <button class="ql-image"></button>
                                                    <button class="ql-video"></button>
                                                </span>
                                                <span class="ql-formats">
                                                    <button class="ql-clean"></button>
                                                </span>
                                            </div>
                                            <?= Html::activeTextarea($model, 'content', [
                                                'placeholder' => 'content',
                                                'class' => 'field',
                                                'style' => 'display: none; min-height: 400px; text-transform: none;',
                                            ]) ?>
                                            <div id="quill-html" class="text-content"><?= $model->content ?></div>
                                        </div>
                                        <?= Html::error($model, 'content', ['class' => 'field-error']) ?>
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
                                <div class="prop">Date <span>*</span></div>
                            </div>
                            <div class="control-content">
                                <div class="control-fields">

                                    <div class="control-field control-field--sm <?= $model->hasErrors('published') ? 'error' : '' ?>">
                                        <div class="datepicker-el js-datepicker datepicker-el--style-2 <?= $model->published ? 'selected' : '' ?>">
                                            <div class="datepicker-el__btn field">
                                                <div class="datepicker-el__btn-text prop"><?= $model->published ?: 'choose' ?></div>
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
                                                    <input class="datepicker-el__input" name="<?= $model->formName() ?>[published]" value="<?= $model->published ?>" type="text" readonly="true">
                                                </div>
                                            </div>
                                        </div>
                                        <?= Html::error($model, 'published', ['class' => 'field-error']) ?>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="control">
                            <div class="control-side">
                                <div class="prop">Published</div>
                            </div>
                            <div class="control-content">

                                <div class="toggler" data-checkbox>
                                    <div class="toggler-checkbox js-on-home">
                                        <div class="checkbox checkbox--toggler">
                                            <label class="checkbox-label" for="scheduleCheckbox3">
                                                <input class="checkbox-input" name="<?= $model->formName() ?>[active]" type="checkbox" id="scheduleCheckbox3" <?= $model->active ? 'checked="checked"' : '' ?>>
                                                <div class="checkbox-content">
                                                    <div class="checkbox-style"></div>
                                                    <div class="checkbox-text h6">Yes</div>
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

                        <div class="control">
                            <div class="control-side">
                                <div class="prop">Content type</div>
                            </div>
                            <div class="control-content" style="flex-wrap: wrap;">

                                <div class="toggler" data-checkbox style="margin-right: 15px">
                                    <div class="toggler-checkbox js-on-home">
                                        <div class="checkbox checkbox--toggler">
                                            <label class="checkbox-label" for="scheduleCheckbox1">
                                                <input class="checkbox-input" name="<?= $model->formName() ?>[is_video]" type="checkbox" id="scheduleCheckbox1" <?= $model->is_video ? 'checked="checked"' : '' ?>>
                                                <div class="checkbox-content">
                                                    <div class="checkbox-style"></div>
                                                    <div class="checkbox-text h6">video / audio</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="toggler" data-checkbox>
                                    <div class="toggler-checkbox js-on-home">
                                        <div class="checkbox checkbox--toggler">
                                            <label class="checkbox-label" for="scheduleCheckbox2">
                                                <input class="checkbox-input" name="<?= $model->formName() ?>[is_text]" type="checkbox" id="scheduleCheckbox2" <?= $model->is_text ? 'checked="checked"' : '' ?>>
                                                <div class="checkbox-content">
                                                    <div class="checkbox-style"></div>
                                                    <div class="checkbox-text h6">text</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <?= Html::error($model, 'is_text', ['class' => 'field-error', 'style' => 'color: #DF0D14 !important; display: block; width: 100%']) ?>

                            </div>

                        </div>

                    </div>
                </div>


                <div class="content-block">
                    <div class="controls">
                        <div class="control control--top">
                            <div class="control-side">
                                <div class="prop">background image <span>*</span></div>
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
                                    <img id="bg_image_preview" src="<?= $model->getThumbnail('bg_image', 1024) ?>">
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

<?php
$js = <<<JS
    var quill = new Quill('#quill-html', {
        modules: {
            toolbar: '#toolbar-container'
        },
        placeholder: 'content', 
        theme: 'snow'
    });
 
    quill.on('text-change', function(delta, oldDelta, source) {
        document.getElementById("mediaform-content").value = quill.root.innerHTML;
    });
JS;
$this->registerJs($js);
?>

            <?= Html::endForm(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
