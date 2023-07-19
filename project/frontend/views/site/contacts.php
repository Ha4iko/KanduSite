<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = 'Contacts';
?>
<main class="main">
    <section class="section section--head">
        <div class="section-bg">
            <div class="section-bg__overlay"><span></span><span></span><span></span></div>
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg9.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="container">
                <div class="section-title">
                    <h1 class="h2">Contacts</h1>
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
                    <div class="mb">If you have any questions or want to create a tournament, use our platform as an organizer, sponsor, developer or streamer, please contact us at these contacts</div>
                    <div class="contacts-items">
                        <div class="contacts-item">
                            <a class="contacts-link" target="_blank" rel="nofollow"
                               href="<?= ArrayHelper::getValue(Yii::$app->params, 'settings.contacts_discord', '#') ?>">
                                <span class="contacts-link__icon">
                                    <svg class="icon">
                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-contacts-discord"></use>
                                    </svg>
                                </span>
                                <span class="h5 contacts-link__text">
                                    Discord
                                </span>
                            </a>
                        </div>
                        <div class="contacts-item">
                            <a class="contacts-link" rel="nofollow"
                               href="mailto:<?= ArrayHelper::getValue(Yii::$app->params, 'settings.contacts_email', '') ?>">
                                <span class="contacts-link__icon">
                                    <svg class="icon">
                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-contacts-email"></use>
                                    </svg>
                                </span>
                                <span class="h5 contacts-link__text">
                                    <?= ArrayHelper::getValue(Yii::$app->params, 'settings.contacts_email', '') ?>
                                </span>
                            </a>
                        </div>
                        <div class="contacts-item">
                            <a class="contacts-link" target="_blank" rel="nofollow"
                               href="<?= ArrayHelper::getValue(Yii::$app->params, 'settings.contacts_tiktok', '#') ?>">
                                <span class="contacts-link__icon">
                                    <svg class="icon">
                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-contacts-tiktok"></use>
                                    </svg>
                                </span>
                                <span class="h5 contacts-link__text">
                                    Tik Tok
                                </span>
                            </a>
                        </div>
                        <div class="contacts-item">
                            <a class="contacts-link" target="_blank" rel="nofollow"
                               href="<?= ArrayHelper::getValue(Yii::$app->params, 'settings.contacts_twitch', '#') ?>">
                                <span class="contacts-link__icon">
                                    <svg class="icon">
                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-contacts-twitch"></use>
                                    </svg>
                                </span>
                                <span class="h5 contacts-link__text">
                                    Twitch                                   
                                </span>
                            </a>
                        </div>
                        <div class="contacts-item">
                            <a class="contacts-link" target="_blank" rel="nofollow"
                               href="<?= ArrayHelper::getValue(Yii::$app->params, 'settings.contacts_youtube', '#') ?>">
                                <span class="contacts-link__icon">
                                    <svg class="icon">
                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-contacts-youtube"></use>
                                    </svg>
                                </span>
                                <span class="h5 contacts-link__text">
                                    YouTube
                                </span>
                            </a>
                        </div>
                        <div class="contacts-item">
                            <a class="contacts-link" target="_blank" rel="nofollow"
                               href="<?= ArrayHelper::getValue(Yii::$app->params, 'settings.contacts_twitter', '#') ?>">
                                <span class="contacts-link__icon">
                                    <svg class="icon">
                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-contacts-twitter"></use>
                                    </svg>
                                </span>
                                <span class="h5 contacts-link__text">
                                    Twitter
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
