<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = 'Donate';
?>
<main class="main">
    <section class="section section--head">
        <div class="section-bg">
            <div class="section-bg__overlay"><span></span><span></span><span></span></div>
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg8.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="container">
                <div class="section-title">
                    <h1 class="h2">Donate</h1>
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
                <div class="contacts">
                    <div class="mb">
                        <?= ArrayHelper::getValue(Yii::$app->params, 'settings.donate_content', '') ?>
                    </div>
                    <div class="contacts-items">
                        <div class="contacts-item">
                            <a class="contacts-link" target="_blank" rel="nofollow"
                               href="<?= ArrayHelper::getValue(Yii::$app->params, 'settings.donate_team', '#') ?>">
                                <span class="h5 contacts-link__text">
                                    Team
                                </span>
                            </a>
                        </div>
                        <div class="contacts-item">
                            <a class="contacts-link" target="_blank" rel="nofollow"
                               href="<?= ArrayHelper::getValue(Yii::$app->params, 'settings.donate_prizepool', '#') ?>">
                                <span class="h5 contacts-link__text">
                                    Prize Pool
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
