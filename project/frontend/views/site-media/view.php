<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\LatestMediaWidget;

/* @var $this yii\web\View */
/* @var $model \frontend\models\Media */

$this->title = $model->title;
?>
<main class="main">
    <section class="section section--head">
        <div class="section-bg">
            <div class="section-bg__overlay"><span></span><span></span><span></span></div>
            <div class="section-bg__img" style="background-image: url(<?= $model->getThumbnail('bg_image', 1920) ?>)"></div>
        </div>
        <div class="section-inner">
            <div class="container">
                <div class="section-back">
                    <a class="link-back" href="<?= Url::to(['/site-media/index']) ?>">
                        <svg class="icon">
                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                        </svg>All media
                    </a>
                </div>
                <div class="section-title">
                    <h1 class="h2"><?= Html::encode($model->title) ?></h1>
                </div>
                <div class="infos js-scroll">
                    <div class="infos-inner">
                        <div class="info">
                            <div class="icon-content">
                                <div class="info-value h6">
                                    <?= Yii::$app->formatter->asDate($model->date, 'php:j F Y') ?>
                                </div>
                                <div class="info-prop prop">date</div>
                            </div>
                        </div>
                        <div class="info">
                            <div class="icon-content">
                                <div class="info-value h6">
                                    <?= $model->typeHtml ?>
                                </div>
                                <div class="info-prop prop">type</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 15px">
                    <?php if (Yii::$app->user->can('root')) : ?>
                        <div class="btn btn--sm js-ajax-popup" data-url="<?= Url::to(['/site-media/update', 'id' => $model->id]) ?>" style="margin-right: 10px">
                            edit
                        </div>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('root')) : ?>
                        <div class="btn btn--sm js-ajax-popup" data-url="<?= Url::to(['/site-media/delete', 'id' => $model->id]) ?>">
                            delete
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <section class="section section--main section--sm">
        <div class="section-bg">
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg5.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="container--sm text-content">
                <?= str_replace('images/', IMG_ROOT . '/', $model->content)  ?>
                <?= $this->render('@frontend/views/tournament/_tournament_share') ?>
            </div>
        </div>
    </section>

    <section class="section section--sm">
        <div class="section-bg">
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg5.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="section-head">
                <div class="container--sm">
                    <div class="section-head__container">
                        <h2 class="h3 section-head__title">other media</h2>
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

                            <?= LatestMediaWidget::widget(['limit' => 3, 'excludeIds' => [$model->id]]) ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

