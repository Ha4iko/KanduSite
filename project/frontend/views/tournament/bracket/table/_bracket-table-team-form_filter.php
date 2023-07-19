<?php

use frontend\models\Bracket;
use frontend\models\BracketTableRowForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this View */
/* @var $model BracketTableRowForm */
/* @var $bracket Bracket */

?>
<?php if (!isset(Yii::$app->params['preview'])) : ?>
<div class="filter filter--admin js-scroll">
    <div class="filter-main">
        <div class="filter-wrap">
            <div class="filter-inner">

                <div class="filter-btns">
                    <div class="filter-btn">
                        <button type="button" class="btn btn--sm js-ajax-popup" data-pjax="0"
                            data-url="<?= Url::to(['/bracket-table/update-bracket-table', 'id' => $bracket->tournament_id, 'bracketId' => $bracket->id]) ?>">
                            Edit bracket
                        </button>
                    </div>
                    <div class="filter-btn">
                        <button type="button" class="btn btn--sm js-ajax-popup" data-pjax="0"
                            data-url="<?= Url::to(['/bracket-table/update-bracket-table-teams', 'id' => $bracket->tournament_id, 'bracketId' => $bracket->id]) ?>">
                            <?= $bracket->bracketTableRowsTeam ? 'Edit' : 'Insert' ?> participants
                        </button>
                    </div>
                </div>

                <div class="filter-search">
                    <div class="filter-search__text">
                        find
                    </div>
                    <div class="filter-search__field">
                        <input class="field field--sm js-table-search" type="text" placeholder="enter name of player">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
$js = <<<JS
    $(document).on('keyup', '.js-table-search', function(event) {
        if (event.keyCode !== 13) return false;
        
        let searchValue = $(this).val();
        
        $('.filter-content tbody tr').each(function() {
            let thisRow = $(this); 
            if (thisRow.find('.js-find-me').text().search(new RegExp(searchValue, "i")) > -1) {
                thisRow.show(); 
            } else {
                thisRow.hide();
            }
        });
    });
    $(document).on('blur', '.js-table-search', function(event) {
        let searchValue = $(this).val();
        
        $('.filter-content tbody tr').each(function() {
            let thisRow = $(this); 
            if (thisRow.find('.js-find-me').text().search(new RegExp(searchValue, "i")) > -1) {
                thisRow.show();
            } else {
                thisRow.hide();
            }
        });
    });
JS;

$this->registerJs($js, View::POS_READY, 'table-search');