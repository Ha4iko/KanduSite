<?php

use frontend\models\BracketCloneForm;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var $this View */
/** @var $model BracketCloneForm */

?>
<div class="popup" id="adminAddBracketGroup">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'bracketCloneForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(['/bracket/clone-bracket', 'id' => $model->id], 'post', [
                'data-pjax' => 1,
                'enctype' => 'multipart/form-data',
            ]); ?>

            <div class="popup-head">
                <div class="popup-close js-popup-close">
                    <a class="link-back" href="#">
                        <svg class="icon">
                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                        </svg>close
                    </a>
                </div>
                <div class="popup-title h3">Clone bracket<span>/ <?= Html::encode($model->old_title) ?></span></div>
            </div>

            <div class="popup-content">

                <div class="content-block">

                    <div class="control">
                        <div class="control-side">
                            <div class="prop">New name of Bracket <span>*</span></div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field <?= $model->hasErrors('title') ? 'error' : '' ?>">
                                    <?= Html::activeTextInput($model, 'title', [
                                        'placeholder' => 'enter new name of bracket',
                                        'class' => 'field'
                                    ]) ?>
                                    <?= Html::error($model, 'title', ['class' => 'field-error']) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="a-footer a-footer--start">
                <button class="btn" type="submit">clone</button>
                <a class="btn js-popup-close" data-pjax="0" href="#">cancel</a>
            </div>

            <?= Html::endForm(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
