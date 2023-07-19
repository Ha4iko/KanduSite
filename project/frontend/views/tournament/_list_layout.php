<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

?>

<?php $this->beginBlock('listViewTemplate'); ?>
    <div class="mb">
        <div class="tourneys tourneys--lg">
            <div class="tourneys-inner">
                {items}
            </div>
        </div>
    </div>

<?php if (Yii::$app->user->can('createTournament')) : ?>
    <div class="mb">
        <div class="hero">
            <picture>
                <source srcset="<?= IMG_ROOT ?>/hero1.webp" type="<?= IMG_ROOT ?>/webp"/>
                <source srcset="<?= IMG_ROOT ?>/hero1.jpg"/><img class="hero-bg" src="<?= IMG_ROOT ?>/hero1.jpg" alt=""/>
            </picture>
            <div class="hero-inner">
                <div class="hero-title">
                    <h4>Don’t see a tournament you like?</h4>
                </div>
                <div class="hero-controls">
                    <button class="btn btn--sm js-ajax-popup" data-url="<?= Url::to(['tournament/create']) ?>">
                        Create own tournament
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

    <div class="catalog-controls">
        <div></div>
        <!--a class="btn btn--dark catalog-show" href="#">show more
            <svg class="icon">
                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-arrow"></use>
            </svg>
        </a-->
        <!-- {more} -->
        {pager}
    </div>
<?php $this->endBlock(); ?>