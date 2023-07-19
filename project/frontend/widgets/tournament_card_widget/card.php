<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model \frontend\models\Tournament */
/* @var $adminMode boolean */

?>
<a class="tourney <?= $adminMode ? 'tourney--admin' : '' ?>" href="<?= $model->url ?>" data-pjax="0">
    <div class="tourney-inner">
        <div class="tourney-media">
            <picture>
                <!--source srcset="<?= IMG_ROOT ?>/tourney1.webp" type="<?= IMG_ROOT ?>/webp"/-->
                <source srcset="<?= $model->getThumbnail('bg_image', 570, 390) ?>"/>
                <img class="tourney-img" src="<?= $model->getThumbnail('bg_image', 570, 390) ?>" alt=""/>
            </picture>
        </div>
        <div class="tourney-content">
            <div class="tourney-top js-scroll">
                <div class="tourney-top__main">
                    <div class="tourney-top__inner">
                        <div class="tourney-status">
                            <div class="tag tag--<?= strtolower(str_replace('In ', '', $model->statusLabel)) ?>">
                                <?= $model->statusLabel ?>
                            </div>
                        </div>
                        <?php if ($adminMode) : ?>
                            <div class="tourney-controls">
                                <div class="tourney-controls__group">
                                    <?php if (Yii::$app->user->can('updateTournament', ['tournamentId' => $model->id])) : ?>
                                        <button class="btn btn--sm js-ajax-popup" type="button" data-pjax="0"
                                            data-url="<?= Url::to(['tournament/update', 'id' => $model->id]) ?>">
                                            edit
                                        </button>
                                    <?php endif; ?>

                                    <?php if (Yii::$app->user->can('updateTournament', ['tournamentId' => $model->id])) : ?>
                                        <button class="btn btn--sm js-ajax-popup" type="button" data-pjax="0"
                                            data-url="<?= Url::to(['tournament/delete', 'id' => $model->id]) ?>">
                                            delete
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="tourney-main">
                <?php if (trim($model->pool_custom)) : ?>
                <div class="tourney-prize">
                    <div class="tag">prize pool <span><?= Html::encode($model->pool_custom) ?></span></div>
                </div>
                <?php endif; ?>
                <div class="tourney-title h5"><?= Html::encode($model->title) ?></div>
                <div class="tourney-info">
                    <div class="tourney-type">
                        <div class="info">
                            <div class="info-icon">
                                <svg class="icon">
                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-shield"></use>
                                </svg>
                            </div>
                            <div class="icon-content">
                                <div class="info-value h6"><?= Html::encode($model->typeName) ?></div>
                                <div class="info-prop prop">type</div>
                            </div>
                        </div>
                    </div>
                    <div class="tourney-date">
                        <div class="date">
                            <div class="date-title h6"><?= Yii::$app->formatter->asDate($model->date, 'php:j F Y') ?></div>
                            <div class="date-prop prop">date</div>
                            <div class="date-icon">
                                <svg class="icon">
                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>