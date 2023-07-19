<?php

use frontend\models\search\TournamentSearch;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;
use frontend\assets\DatepickerWithClearAsset;

/* @var $this View */
/* @var $searchModel TournamentSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $schedules array */

$this->title = 'Tournaments of World of Warcraft';

DatepickerWithClearAsset::register($this);
?>

<?= $this->render('_list_layout') ?>
<?= $this->render('_list_empty') ?>

<?php
$list = \yii\widgets\ListView::widget([
    'options' => [
        'tag' => false
    ],
    'emptyText' => $this->blocks['searchResultIsEmpty'],
    'dataProvider' => $dataProvider,
    'itemView' => '@frontend/widgets/tournament_card_widget/card',
    'itemOptions' => [
        'tag' => false
    ],
    'viewParams' => [
        'searchModel' => $searchModel
    ],
    'layout' => $this->blocks['listViewTemplate'],
    // 'pager' => [
    //     'class' => \frontend\components\pagination\Pagination::class,
    //     'id' => 'tournaments-list-pagination',
    //     'contentSelector' => '.tourneys-inner',
    //     'contentItemSelector' => 'a.tourney',
    //     'includeCssStyles'    => false,
    //     'loaderTemplate' => '',
    //     'onLoad' => new \yii\web\JsExpression('function() { $("#tournaments-list-pagination").addClass("waiting"); }'),
    //     'onAfterLoad' => new \yii\web\JsExpression('function() { updateMasonry(); $("#tournaments-list-pagination").removeClass("waiting"); }'),
    //     'template' => '{button}',
    //     'buttonText' => 'show more <svg class="icon">
    //                 <use href="' . IMG_ROOT . '/sprites/main.symbol.svg#image-arrow"></use>
    //               </svg>',
    //     'options' => ['class' => 'btn btn--dark catalog-show']
    // ],
    'pager' => [
        'class' => \frontend\components\pagination\LinkPager::class,
        'options' => [
            'class' => 'pagination',
            'tag' => 'div'
        ],
        'maxButtonCount' => 5,
        'internalLinksWrapperClass' => 'pagination-list',
        'pageCssClass' => '',
        'externalLinksTag' => false,
        'linkContainerOptions' => [
            'tag' => 'li',
            'class' => 'pagination-item',
        ],
        'linkOptions' => [
            'class' => 'pagination-link btn btn--dark',
            'data-pjax' => '0'
        ],
        'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => ''],
        'disabledPageCssClass' => 'disabled',
        'prevPageCssClass' => 'pagination-btn btn btn--dark',
        'nextPageCssClass' => 'pagination-btn btn btn--dark',
        'activePageCssClass' => 'active',
        'nextPageLabel' => 'next',
        'prevPageLabel' => 'prev',
    ],
    
]);

?>

<main class="main">
    <?php Pjax::begin([
        'id' => 'tournament-list',
        'timeout' => 5000
    ]) ?>
        <section class="section section--head">
            <div class="section-bg">
                <div class="section-bg__overlay"><span></span><span></span><span></span></div>
                <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg14.jpg)"></div>
            </div>
            <div class="section-inner">
                <div class="container">
                    <div class="section-title">
                        <h1 class="h2">all tournaments <span>/ <?= $dataProvider->totalCount ?></span></h1>
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
                    <?= $this->render('_index_filter', ['model' => $searchModel]) ?>
                    <div class="filter-content">
                        <?= $list ?>
                    </div>
                    <?= $this->render('_index_filter', ['model' => $searchModel]) ?>
                </div>
            </div>
        </section>
    <?php Pjax::end() ?>

    <?= $this->render('_index_schedule', [
            'schedules' => $schedules
        ]) ?>
</main>

<?php
$js = <<<JS
    datepickerWithClear();
JS;

$this->registerJs($js, View::POS_READY, 'dpicker-clear');