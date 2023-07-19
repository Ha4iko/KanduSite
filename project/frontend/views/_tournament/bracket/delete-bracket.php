<?php

/** @var $this \yii\web\View */
/** @var $model \frontend\models\Bracket */

use yii\helpers\Html;
use yii\widgets\Pjax;

?>

<div class="popup" id="deleteBracketConfirmation">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'timeout' => 5000,
                'id' => 'delete-bracket-form'
            ]) ?>
            <?= Html::beginForm(['bracket/delete-bracket', 'id' => $model->id], 'post', ['data-pjax' => 1]) ?>
                <div class="popup-head">
                    <div class="popup-close js-popup-close">
                        <a class="link-back" href="#">
                            <svg class="icon">
                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>close</a>
                    </div>
                    <div class="popup-title h3 primary">are you sure?</div>
                </div>
                <input id="tournamentId" type="hidden" name="tournamentId">
                <div class="popup-content">
                    <div class="content-block">
                        <p class="secondary">Confirm bracket deletion</p>
                    </div>
                </div>
                <div class="a-footer a-footer--start">
                    <button type="submit" class="btn" href="#">i’m sure</button>
                    <a class="btn js-popup-close" href="#">cancel</a>
                </div>
            <?= Html::endForm() ?>
            <?php Pjax::end() ?>
        </div>
    </div>
</div>
