<?php

use yii\web\View;
use yii\helpers\Html;
use frontend\models\search\ChampionSearch;
use common\helpers\DataTransformHelper;
use frontend\models\TournamentType;
use yii\helpers\ArrayHelper;

/* @var $this View */
/* @var $model ChampionSearch */

$typeNames = ArrayHelper::merge([null => 'All types'], TournamentType::getTypeNames());

$js = <<<JS
    $(document).on('change', '.js-filter select, .js-filter input', function(e) {
        $(e.target).closest('form').submit();
    });

    $(document).on('datepickerclearupdated', '.js-filter .js-datepicker-clear', function(e) {
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


!Yii::$app->request->isPjax && $this->registerJs($js, View::POS_READY, 'js-filter');


?>
<?= Html::beginForm(['tournament/champions'], 'get', ['class' => 'js-filter', 'data-pjax' => 1]) ?>
<div class="filter js-scroll">
    <div class="filter-main">
        <div class="filter-wrap">
            <div class="filter-inner">
                <div class="filter-search">
                    <div class="filter-search__text">find</div>
                    <div class="filter-search__field">
                        <?= Html::activeTextInput($model, 'name', [
                            'placeholder' => 'enter name of player',
                            'class' => 'field field--sm',
                            'name' => 'name',
                        ]) ?>
                    </div>
                </div>
                <div class="filter-items">
                    <div class="filter-item">
                        <div class="filter-item__text prop">date:</div>
                        <div class="filter-item__value">
                            <div class="datepicker-el js-datepicker-clear <?= $model->date ? 'selected' : '' ?>">
                                <div class="datepicker-el__btn">
                                    <div class="datepicker-el__btn-text prop"><?= $model->date ?: 'choose' ?></div>
                                    <div class="datepicker-el__btn-icon">
                                        <svg class="icon">
                                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="datepicker-el__drop">
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
                                    <div class="datepicker-el__inner">
                                        <?= Html::activeTextInput($model, 'date', [
                                            'class' => 'datepicker-el__input',
                                            'name' => 'date',
                                            'autocomplete' => 'off'
                                            // readonly="true" ???
                                        ]) ?>
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
                                        ChampionSearch::getOrderLabels(),
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