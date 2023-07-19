<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

?>

<?php $this->beginBlock('listViewTemplate'); ?>
    <div class="mb">
        <div class="news news--lg">
            <div class="news-inner">
                {items}
            </div>
        </div>
    </div>

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