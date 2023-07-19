<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Tournament;
use yii\web\View;

/* @var $this View */
/* @var $model Tournament */

$this->title = 'Schedule | ' . Html::encode($model->title);

$tournamentSchedules = $model->tournamentSchedules;

?>

<?php if (!isset(Yii::$app->params['preview']) && Yii::$app->user->can('updateTournament', ['tournamentId' => $model->id])) : ?>
<div class="container--sm">
    <div class="filter filter--admin js-scroll">
        <div class="filter-main">
            <div class="filter-wrap">
                <div class="filter-inner">
                    <div class="filter-btns">
                        <div class="filter-btn">
                            <button class="btn btn--sm js-ajax-popup" data-url="<?= Url::to(['schedule/update', 'id' => $model->id]) ?>">
                                <?= !count($tournamentSchedules) ? 'Add' : 'Edit' ?> schedule
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="filter-content">
<?php endif; ?>



<?php if (empty($tournamentSchedules)) : ?>
    <?= $this->render('_list_empty', ['renderInPlace' => true]) ?>
<?php else : ?>
    <div class="schedule-slider container">
        <div class="js-schedule-slider swiper-container">
            <div class="swiper-wrapper">
                <?php foreach ($tournamentSchedules as $tournamentSchedule) : ?>
                <div class="swiper-slide">
                    <div class="schedule-slide">
                        <div class="schedule-slide__top">
                            <div class="h6 schedule-slide__title"><?= Html::encode($tournamentSchedule->title) ?></div>
                        </div>
                        <div class="schedule-slide__bottom">
                            <div class="schedule-slide__date">
                                <div class="h6"><?= Yii::$app->formatter->asDate($tournamentSchedule->date, 'php:j F Y') ?></div>
                                <div class="prop"><?= Yii::$app->formatter->asTime($tournamentSchedule->time, 'php:H:i') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-prev">
                <div class="btn">
                    <svg class="icon">
                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                    </svg>
                </div>
            </div>
            <div class="swiper-button-next">
                <div class="btn">
                    <svg class="icon">
                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                    </svg>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>



<?php if (!isset(Yii::$app->params['preview']) && Yii::$app->user->can('updateTournament', ['tournamentId' => $model->id])) : ?>
</div>

<div class="container--sm">
    <div class="filter filter--admin js-scroll">
        <div class="filter-main">
            <div class="filter-wrap">
                <div class="filter-inner">
                    <div class="filter-btn">
                        <button class="btn btn--sm js-ajax-popup" data-url="<?= Url::to(['schedule/update', 'id' => $model->id]) ?>">
                            edit schedule
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="container--sm">
<?= $this->render('_tournament_share') ?>
</div>