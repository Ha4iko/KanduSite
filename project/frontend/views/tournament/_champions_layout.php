<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

?>

<?php $this->beginBlock('listChampionsTemplate'); ?>
    <div class="mb">
        <div class="champs champs--lg">
            <div class="champs-inner">
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