<?php

use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\search\TournamentSearch;
use common\helpers\DataTransformHelper;
use frontend\models\TournamentType;
use yii\helpers\ArrayHelper;

/* @var $this View */
/* @var $model TournamentSearch */

$typeNames = ArrayHelper::merge([null => 'All types'], TournamentType::getTypeNames());

$js = <<<JS
    $(document).on('blur', '.js-filter input', function(e) {
        $(e.target).closest('form').submit();
    });
    $(document).on('change', '.js-filter select', function(e) {
        $(e.target).closest('form').submit(); 
    });

    $(document).on('submit', '.js-filter', function () {
        $(this)
            .find('input[name],select[name]')
            .filter(function () {
                return !this.value;
            })
            .prop('name', '');
    });
JS;

if (!Yii::$app->request->isPjax) {
    $this->registerJs($js, View::POS_READY, 'js-filter');
}

?>
<?= Html::beginForm(['cabinet/tournaments'], 'get', ['class' => 'js-filter', 'data-pjax' => 1]) ?>
    <div class="filter filter--admin js-scroll">
        <div class="filter-main">
            <div class="filter-wrap">
                <div class="filter-inner">
                    <div class="filter-btns">
                        <div class="filter-btn">
                            <button type="button" class="btn btn--sm js-ajax-popup"
                                data-pjax="0" data-url="<?= Url::to(['tournament/create']) ?>">
                                create tournament
                            </button>
                        </div>
                        <div class="filter-btn">
                            <div class="filter-search">
                                <div class="filter-search__text">find</div>
                                <div class="filter-search__field">
                                    <?= Html::activeTextInput($model, 'title', [
                                        'placeholder' => 'enter name',
                                        'class' => 'field field--sm',
                                        'name' => 'title',
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="filter-items">
                        <div class="filter-item">
                            <div class="filter-item__text prop">status:</div>
                            <div class="filter-item__value">
                                <div class="select">
                                    <div class="select-btn">
                                        <?= Html::activeDropDownList($model, 'status',
                                            ArrayHelper::merge([null => 'All'], TournamentSearch::getStatusLabelsBySlugs()),
                                            [
                                                'id' => 'select' . uniqid(),
                                                'name' => 'status',
                                                'data-style' => '1',
                                                'class' => 'js-select'
                                            ]
                                        ) ?>
                                    </div>
                                    <div class="select-drop">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="filter-item">
                            <div class="filter-item__text prop">type:</div>
                            <div class="filter-item__value">
                                <div class="select">
                                    <div class="select-btn">
                                        <?= Html::activeDropDownList($model, 'type',
                                            $typeNames,
                                            [
                                                'id' => 'select' . uniqid(),
                                                'name' => 'type',
                                                'data-style' => '1',
                                                'class' => 'js-select'
                                            ]
                                        ) ?>
                                    </div>
                                    <div class="select-drop">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="filter-item">
                            <div class="filter-item__text prop">sort by:</div>
                            <div class="filter-item__value">
                                <div class="select">
                                    <div class="select-btn">
                                        <?= Html::activeDropDownList($model, 'order',
                                            TournamentSearch::getOrderLabels(),
                                            [
                                                'id' => 'select' . uniqid(),
                                                'name' => 'order',
                                                'data-style' => '1',
                                                'class' => 'js-select'
                                            ]
                                        ) ?>
                                    </div>
                                    <div class="select-drop">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= Html::endForm() ?>