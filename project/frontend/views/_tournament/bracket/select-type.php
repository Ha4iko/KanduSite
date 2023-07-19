<?php

use frontend\models\Tournament;
use yii\web\View;
use Url;

/** @var $this View */
/** @var $model Tournament */

$is1vs1and5x5 = $model->type_id == 2;
?>
<div class="popup" id="adminAddBracketType">
    <div class="popup-wrap">
        <div class="popup-main">

            <div class="popup-head">
                <div class="popup-close js-popup-close"><a class="link-back" href="#">
                        <svg class="icon">
                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                        </svg>close</a>
                </div>
                <div class="popup-title h3">edit bracket <span>/ choose type</span></div>
            </div>

            <div class="popup-content">
                <div class="content-block">
                    <div class="type-controls">
                        <?php if (!$is1vs1and5x5) : ?>
                        <div class="type-control">
                            <div class="type-control__radio">
                                <div class="radio">
                                    <label class="radio-label" for="bracketType1">
                                        <input class="radio-input" type="radio" name="bracketType" id="bracketType1">
                                        <div class="radio-content">
                                            <div class="radio-style"></div>
                                            <div class="radio-text h6">Elimination</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="type-control__content">
                                <img class="type-control__img" src="<?= IMG_ROOT ?>/bracket-type1.svg" alt="">
                                <div class="type-control__text">
                                    In most multi-table tournaments, you are considered eliminated when you run out of chips. Some tournaments, however, will allow you to buy in more than once.
                                </div>
                            </div>
                        </div>

                        <div class="type-control">
                            <div class="type-control__radio">
                                <div class="radio">
                                    <label class="radio-label" for="bracketType2">
                                        <input class="radio-input" type="radio" name="bracketType" id="bracketType2">
                                        <div class="radio-content">
                                            <div class="radio-style"></div>
                                            <div class="radio-text h6">Group</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="type-control__content">
                                <img class="type-control__img" src="<?= IMG_ROOT ?>/bracket-type2.svg" alt="">
                                <div class="type-control__text">
                                    The winner of a multi-table tournament is the person who has all of the chips in the end. The other places are determined based on order of elimination.
                                </div>
                            </div>
                        </div>

                        <div class="type-control">
                            <div class="type-control__radio">
                                <div class="radio">
                                    <label class="radio-label" for="bracketType3">
                                        <input class="radio-input" type="radio" name="bracketType" id="bracketType3">
                                        <div class="radio-content">
                                            <div class="radio-style"></div>
                                            <div class="radio-text h6">swiss</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="type-control__content">
                                <img class="type-control__img" src="<?= IMG_ROOT ?>/bracket-type3.svg" alt="">
                                <div class="type-control__text">
                                    One of the largest and most recognized multi-table tournaments in the world is the World Series of Poker main event. Thousands of people from all over the world put down $10,000 each year to try and win millions of dollars. Names such as Phil Hellmuth, Johnny Chan and Doyle Brunson have all won the World Series of Poker main event.
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="type-control">
                            <div class="type-control__radio">
                                <div class="radio">
                                    <label class="radio-label" for="bracketType4">
                                        <input class="radio-input" type="radio" name="bracketType" id="bracketType4">
                                        <div class="radio-content">
                                            <div class="radio-style"></div>
                                            <div class="radio-text h6">table</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="type-control__content">
                                <img class="type-control__img" src="<?= IMG_ROOT ?>/bracket-type4.svg" alt="">
                                <div class="type-control__text">
                                    In most multi-table tournaments, blinds will regularly increase and antes will eventually be added.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="a-footer a-footer--start">
                <button type="button" class="btn js-bracket-type waiting" data-id="<?= $model->id ?>">
                    next
                </button>

                <a class="btn js-popup-close" href="#">cancel</a>
            </div>

        </div>
    </div>
</div>


<?php
$js = <<<JS
    $(document).on('change', 'input[name=bracketType]', function(e) {
        $('.js-bracket-type').removeClass('waiting');        
    });
    
    $(document).on('click', '.js-bracket-type', function(e) {
        let selectedType = $('input[name=bracketType]:checked').attr('id');

        let newPopupUrl = '';
        if ('bracketType1' === selectedType) {
            popupFastClose($(this).closest(".popup"));
            newPopupUrl = '/bracket-relegation/update-bracket?id=' + $(this).attr('data-id');
        }
        if ('bracketType2' === selectedType) {
            popupFastClose($(this).closest(".popup"));
            newPopupUrl = '/bracket-group/update-bracket?id=' + $(this).attr('data-id');
        }
        if ('bracketType3' === selectedType) {
            popupFastClose($(this).closest(".popup"));
            newPopupUrl = '/bracket-swiss/update-bracket?id=' + $(this).attr('data-id');
        }
        if ('bracketType4' === selectedType) {
            popupFastClose($(this).closest(".popup"));
            newPopupUrl = '/bracket-table/update-bracket-table?id=' + $(this).attr('data-id');
        }
        
        if (!newPopupUrl) return false;
        
        ajaxPopup(newPopupUrl);
    });
JS;
$this->registerJs($js, \yii\web\View::POS_READY, 'js-bracket-type');