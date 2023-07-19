<?php

use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $nicks array */

$this->title = 'Thanks';
?>
<main class="main">
    <section class="section section--head">
        <div class="section-bg">
            <div class="section-bg__overlay"><span></span><span></span><span></span></div>
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg7.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="container">
                <div class="section-title">
                    <h1 class="h2">thanks</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="section section--main section--sm">
        <div class="section-bg">
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg5.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="container--sm">
                <div class="thanks">
                    <div class="mb">
                        We would like to express our gratitude and say a big thank you to all those who help us
                    </div>
                    <div class="thanks-items">
                        <?php foreach ($nicks as $nick) : ?>
                        <div class="thanks-item"><?= Html::encode($nick) ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
