<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Tournament;
use frontend\models\search\BracketTableRowSearch;

/* @var $this View */
/* @var $model Tournament */
/* @var $searchModel BracketTableRowSearch */
/* @var $isAdmin bool */

$js = <<<JS
    $(document).on('blur', '.js-filter input', function(e) {
        $(e.target).closest('form').submit();
    }); 

    $(document).on('submit', '.js-filter', function () {
        $(this)
            .find('input[name]')
            .filter(function () {
                return !this.value;
            })
            .prop('name', '');
    });
JS;

!Yii::$app->request->isPjax && $this->registerJs($js, View::POS_READY, 'js-filter');

?>
<?= Html::beginForm([
    '/tournament/brackets', 'slug' => $model->slug, 'id' => $searchModel->bracketId],
    'get',
    ['class' => 'js-filter', 'data-pjax' => 1]
) ?>
<div class="filter mb js-scroll">
    <div class="filter-main">
        <div class="filter-wrap">
            <div class="filter-inner">

                <div class="filter-search">
                    <div class="filter-search__text">
                        find
                    </div>
                    <div class="filter-search__field">
                        <?= Html::activeTextInput($searchModel, 'nick', [
                            'placeholder' => 'enter name of player',
                            'class' => 'field field--sm',
                            'name' => 'nick',
                        ]) ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?= Html::endForm(); ?>
