<?php

use frontend\models\Bracket;
use frontend\models\BracketTableRowForm;
use frontend\models\Tournament;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;
use frontend\assets\DataTablesAsset;

/* @var $this View */
/* @var $model BracketTableRowForm */
/* @var $tournament Tournament */
/* @var $bracket Bracket */

$this->title = 'Bracket table of tournament';

$tableRows = $model ? $model->getTableRows() : [];
$existRows = 0;
foreach ($tableRows as $row) {
    if ($row['id']) $existRows++;
}
if ($existRows) DataTablesAsset::register($this);
?>

<?php if (is_object($bracket)) : ?>
<!--    --><?php //Pjax::begin([
//        'id' => 'bracket-table-form',
//        'timeout' => 5000,
//        'enablePushState' => false,
//        'enableReplaceState' => false,
//    ]) ?>
    <?= Html::beginForm('', 'post', [
        'id' => 'bracketForm',
//        'data-pjax' => 1,
        //'enctype' => 'multipart/form-data',
    ]); ?>
    <input type="hidden" name="Tournament[id]" value="<?= $tournament->id ?>">
    <input type="hidden" name="Tournament[status]" value="<?= $tournament->status ?>">

    <?= $this->render('_bracket-table-form_filter', [
        'model' => $model,
        'bracket' => $bracket,
    ]) ?>

    <div class="filter-content filter-content--sm">
        <?= $this->render('_bracket-table-form_layout', [
            'model' => $model,
            'tableRows' => $tableRows,
            'existRows' => $existRows,
        ]) ?>
    </div>

    <?= $this->render('_bracket-table-form_filter', [
        'model' => $model,
        'bracket' => $bracket,
    ]) ?>

    <?= Html::endForm(); ?>
<!--    --><?php //Pjax::end() ?>

<?php else : ?>
    <?= $this->render('_list_empty', ['renderInPlace' => true]) ?>
<?php endif; ?>


<?php
$js = <<<JS
    $(document).on('blur', '.js-cell-edit', function () {
        if ($('.js-cell-edit.active').length) {
            if (
                $('.js-cell-edit.active').find('.cell-edit__input').val().length > 0
            ) {
                $('.js-cell-edit.active')
                    .find('.cell-edit__text')
                    .text($('.js-cell-edit.active').find('.cell-edit__input').val());
                $('.js-cell-edit.active').removeClass('active');
            }
        }
    });

    $('.filter-content table').DataTable({
        paging: false,
        searching: false,
        select: false,
        info: false
    }); 
JS;

if ($existRows) $this->registerJs($js);