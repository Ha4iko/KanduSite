<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
$jsShare = 'Share';
$js = <<<JS
    $('.js-target-window').click(function(e) {
        if (window.innerWidth > 1024) {
            var el = $(this);
            e.preventDefault();
            window.open(el.attr('href'), '{$jsShare}', 'width=600,height=450');
            return false;
        }
    });
JS;
$this->registerJs($js, \yii\web\View::POS_READY, 'js-share');
?>
<div class="share">
    <div class="share-title h6">Share this event</div>
    <ul class="share-list">
        <li class="share-item">
            <a target="_blank" rel="nofollow" class="share-link js-target-window"
               href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(Url::current([], true)) ?>">
                facebook
            </a>
        </li>
        <li class="share-item">
            <a target="_blank" rel="nofollow" class="share-link js-target-window"
               href="http://vk.com/share.php?url=<?= urlencode(Url::current([], true)) ?>">
                vk
            </a>
        </li>
        <li class="share-item">
            <a target="_blank" rel="nofollow" class="share-link js-target-window"
               href="http://twitter.com/share?url=<?= urlencode(Url::current([], true)) ?>">
                twitter
            </a>
        </li>
    </ul>
    <div class="share-drop">
        <div class="dropdown dropdown--sm">
            <div class="dropdown-result js-dropdown-btn">
                <div class="dropdown-result__text">more</div>
                <div class="dropdown-result__icon">
                    <svg class="icon">
                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                    </svg>
                </div>
            </div>
            <div class="dropdown-box">
                <div class="close js-close">
                    <div class="close-inner">
                        <div class="close-icon">
                            <svg class="icon">
                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>
                        </div>
                        <div class="close-text">close</div>
                    </div>
                </div>
                <div class="dropdown-box__inner">
                    <ul class="dropdown-items">
                        <li class="dropdown-item">
                            <a target="_blank" rel="nofollow" class="dropdown-link share-link js-target-window"
                               href="tg://msg?text=<?= urlencode(Url::current([], true)) ?>">
                                telegram
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
