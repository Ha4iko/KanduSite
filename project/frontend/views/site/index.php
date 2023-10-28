<?php

use frontend\widgets\LatestTournamentsWidget;
use frontend\widgets\LatestMediaWidget;
use frontend\widgets\ChampionCardWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\TournamentType;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $tournamentTypes TournamentType[] */
/* @var $championsRelations array */

$this->title = 'Tournaments of World of Warcraft';
?>
<main class="main">

    <section class="section section--hero">
        <div class="section-bg">
            <div class="section-bg__overlay"><span></span><span></span><span></span></div>
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg1.jpg)"></div>
            <div class="section-img">
                <picture>
                    <source srcset="<?= IMG_ROOT ?>/section-img2.webp" type="<?= IMG_ROOT ?>/webp"/>
                    <source srcset="<?= IMG_ROOT ?>/section-img2.png"/>
                    <img src="<?= IMG_ROOT ?>/section-img2.png" alt=""/>
                </picture>
            </div>
        </div>
        <div class="section-inner">
            <div class="container">
                <div class="section-row">
                    <div class="section-col">
                        <h1>
                            <?= ArrayHelper::getValue(Yii::$app->params, 'settings.home_intro_title') ?>
                        </h1>
                        <div class="descr">
                            <?= ArrayHelper::getValue(Yii::$app->params, 'settings.home_intro_desc') ?>
                        </div>
                        <a class="btn" href="<?= ArrayHelper::getValue(Yii::$app->params, 'settings.home_intro_btn_link') ?>">
                            <?= ArrayHelper::getValue(Yii::$app->params, 'settings.home_intro_btn_text') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-inner">
            <div class="section-head">
                <div class="container--sm">
                    <div class="section-head__container">
                        <h2 class="h3 section-head__title">latest tournaments</h2>
                        <a class="section-head__link" href="<?= Url::to(['/tournament/index']) ?>">All tournaments
                            <svg class="icon">
                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="section-content">
                <div class="container">
                    <div class="tourneys js-scroll">
                        <div class="tourneys-inner">
                            <?= LatestTournamentsWidget::widget(['limit' => 6, 'noPending' => true]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-bg">
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg2.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="section-head">
                <div class="container--sm">
                    <div class="section-head__container">
                        <h2 class="h3 section-head__title">Choose your type</h2>
                        <a class="section-head__link" href="<?= Url::to(['/tournament/index']) ?>">All tournaments
                            <svg class="icon">
                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="section-content">
                <div class="container--sm">
                    <div class="types">
                        <?php foreach ($tournamentTypes as $tournamentType) : ?>
                        <a class="type" href="<?= Url::to(['/tournament/index', 'type' => $tournamentType->slug]) ?>">
                            <div class="type-inner">
                                <div class="type-media">
                                    <picture>
                                        <source srcset="<?= IMG_ROOT ?>/type1.webp" type="<?= IMG_ROOT ?>/webp"/>
                                        <source srcset="<?= IMG_ROOT ?>/type1.jpg"/>
                                        <img class="type-img" src="<?= IMG_ROOT ?>/type1.jpg" alt=""/>
                                    </picture>
                                </div>
                                <div class="type-content">
                                    <h4 class="type-title"><?= Html::encode($tournamentType->name) ?></h4>
                                    <div class="type-tag">
                                        <div class="tag tag--dark">
                                            <?= Html::encode($tournamentType->description) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-inner">
            <div class="section-head">
                <div class="container--sm">
                    <div class="section-head__container">
                        <h2 class="h3 section-head__title">hall of fame</h2>
                        <a class="section-head__link" href="<?= Url::to(['/tournament/champions']) ?>">All champions
                            <svg class="icon">
                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="section-content">
                <div class="container">
                    <div class="champs js-scroll">
                        <div class="champs-inner">
                            <div class="champs-item">
                                <div class="champs-head">
                                    <div class="champs-head__media">
                                        <picture>
                                            <source srcset="<?= IMG_ROOT ?>/champ-head.webp" type="<?= IMG_ROOT ?>/webp"/>
                                            <source srcset="<?= IMG_ROOT ?>/champ-head.png"/>
                                            <img class="champs-head__img" src="<?= IMG_ROOT ?>/champ-head.png" alt=""/>
                                        </picture>
                                    </div>
                                    <div class="champs-head__content">
                                        <div class="champs-head__title h1">you might be here</div>
                                        <a class="btn" href="<?= Url::to(['/tournament/index']) ?>">find your tournament</a>
                                    </div>
                                </div>
                            </div>

                            <?php foreach ($championsRelations as $championsRelation) : ?>
                                <?= ChampionCardWidget::widget([
                                    'championsRelation' => $championsRelation,
                                ]) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-bg">
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg3.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="container">
                <div class="section-row">
                    <div class="section-col">
                        <h2 class="h1">
                            <?= ArrayHelper::getValue(Yii::$app->params, 'settings.home_trailer_title', '') ?>
                        </h2>
                        <div class="descr">
                            <?= ArrayHelper::getValue(Yii::$app->params, 'settings.home_trailer_desc', '') ?>
                        </div>
                        <a class="btn" target="_blank" rel="nofollow"
                           href="<?= ArrayHelper::getValue(Yii::$app->params, 'settings.home_trailer_link', '') ?>">
                            watch on youtube
                        </a>
                    </div>
                    <div class="section-col">
                        <div class="video">
                            <div class="video-btn">
                                <svg class="icon">
                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-play"></use>
                                </svg>
                            </div>
                            <div class="video-bg">
                                <picture>
                                    <source srcset="<?= IMG_ROOT ?>/trailer.webp" type="<?= IMG_ROOT ?>/webp"/>
                                    <source srcset="<?= IMG_ROOT ?>/trailer.jpg"/>
                                    <img src="<?= IMG_ROOT ?>/trailer.jpg" alt=""/>
                                </picture>
                            </div>
                            <div class="video-content">
                                <iframe width="100%" height="100%"
                                        src="<?= ArrayHelper::getValue(Yii::$app->params, 'settings.home_trailer_link', '') ?>"
                                        title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>                                       
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-inner">
            <div class="section-head">
                <div class="container--sm">
                    <div class="section-head__container">
                        <h2 class="h3 section-head__title">media</h2>
                        <a class="section-head__link" href="<?= Url::to(['/site-media/index']) ?>">All media
                            <svg class="icon">
                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="section-content">
                <div class="container">
                    <div class="news js-scroll">
                        <div class="news-inner">
                            <?= LatestMediaWidget::widget(['limit' => 3]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
