<?php

/* @var $this yii\web\View */

$renderInPlace = $renderInPlace ?? false;
?>

<?php $this->beginBlock('searchResultIsEmpty', $renderInPlace); ?>
    <section class="section section--main section--sm">
        <div class="section-bg">
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg5.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="container--sm">
                <div class="empty">
                    <div class="empty-text js-fittext" style="white-space: nowrap; display: inline-block; font-size: 96px;">still empty here</div>
                </div>
            </div>
        </div>
    </section>
<?php $this->endBlock(); ?>