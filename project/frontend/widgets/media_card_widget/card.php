<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model \frontend\models\Media */
/* @var $adminMode boolean */

?>
<a class="news-item<?= $model->active ? '' : ' not-active' ?>" href="<?= $model->url ?>" data-pjax="0">
    <div class="news-item__inner">
        <div class="news-item__media">
            <picture>
                <source srcset="<?= $model->getThumbnail('bg_image', 570, 390) ?>"/>
                <img class="news-item__img" src="<?= $model->getThumbnail('bg_image', 570, 390) ?>" alt=""/>
            </picture>
        </div>
        <div class="news-item__content">
            <div class="news-item__tags">
                <div class="tags">
                    <?php if ($model->is_video) : ?>
                    <div class="tag tag--sm">video</div>
                    <?php endif; ?>

                    <?php if ($model->is_text) : ?>
                    <div class="tag tag--sm">text</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="news-item__main">
                <div class="news-item__title h5"><?= Html::encode($model->title) ?></div>
                <div class="news-item__date">
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
</a>