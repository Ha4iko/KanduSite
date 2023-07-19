<?php

use frontend\models\search\UserSearch;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this View */
/* @var $searchModel UserSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Users';

?>

<?= $this->render('_index_layout') ?>
<?= $this->render('@frontend/views/tournament/_list_empty') ?>

<?php
$list = \yii\widgets\ListView::widget([
    'options' => [
        'tag' => false
    ],
    'emptyText' => $this->blocks['searchResultIsEmpty'],
    'dataProvider' => $dataProvider,
    'itemView' => '@frontend/views/cabinet/_index_user',
    'itemOptions' => [
        'tag' => false
    ],
    'viewParams' => [
        'adminMode' => true
    ],
    'layout' => $this->blocks['listUsersTemplate'],
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
        'id' => 'cabinetUsers',
        'timeout' => 5000,
    ]); ?>
    <section class="section section--head">
        <div class="section-bg">
            <div class="section-bg__overlay"><span></span><span></span><span></span></div>
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg14.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="container">
                <div class="section-title">
                    <h1 class="h2">users <span>/ <?= $dataProvider->totalCount ?></span></h1>
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
                <div class="filter-content filter-content--sm">
                    <?= $list ?>
                </div>
                <?= $this->render('_index_filter', ['model' => $searchModel]) ?>
            </div>
        </div>
    </section>
    <?php Pjax::end(); ?>
</main>
