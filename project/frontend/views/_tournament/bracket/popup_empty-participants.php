<?php

/** @var $this \yii\web\View */
/** @var $model \frontend\models\Tournament */

use yii\helpers\Html;
use yii\widgets\Pjax;

?>

<div class="popup" id="emptyTournamentParticipants">
    <div class="popup-wrap">
        <div class="popup-main">
            <div class="popup-head">
                <div class="popup-close js-popup-close">
                    <a class="link-back" href="#">
                        <svg class="icon">
                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                        </svg>close</a>
                </div>
                <div class="popup-title h3 primary">Empty participants</div>
            </div>
            <input id="tournamentId" type="hidden" name="tournamentId">
            <div class="popup-content">
                <div class="content-block">
                    <p class="secondary">First add participants to this tournament.</p>
                </div>
            </div>
            <div class="a-footer a-footer--start">
                <a class="btn js-popup-close" href="#">cancel</a>
            </div>
        </div>
    </div>
</div>
