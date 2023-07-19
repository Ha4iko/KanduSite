<?php

use yii\data\ArrayDataProvider;
use yii\widgets\ListView;
use frontend\components\pagination\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Bracket;
use frontend\models\Tournament;
use frontend\models\search\BracketTableRowSearch;
use yii\web\View;
use yii\widgets\Pjax;
use frontend\assets\DataTablesAsset;

/* @var $this View */
/* @var $model Tournament */
/* @var $bracket Bracket */
/* @var $searchModel BracketTableRowSearch */
/* @var $dataProvider ArrayDataProvider */

$this->title = 'Bracket table of tournament';

$isAdmin = Yii::$app->user->can('updateTournament', ['tournamentId' => $model->id]);

$existRows = 0;
$tableHeaders = [];
foreach ($dataProvider->getModels() as $row) {
    if ($row['id']) $existRows++;
    foreach ($row['columns'] ?? [] as $col) {
        $tableHeaders[$col['id']] = $col['title'];
    }
}
if ($existRows) {
    DataTablesAsset::register($this);
} else {
    $dataProvider->setModels([]);
}

?>

<?= $this->render('_bracket-table_layout', ['dataProvider' => $dataProvider, 'isAdmin' => $isAdmin]) ?>
<?= $this->render('@frontend/views/tournament/_list_empty') ?>

<?php

$list = ListView::widget([
    'options' => [
        'tag' => false
    ],
    'emptyText' => $this->render('_empty_rows_guest_players', ['tableHeaders' => $tableHeaders]),
    'dataProvider' => $dataProvider,
    'itemView' => '_bracket-table_row',
    'itemOptions' => [
        'tag' => false
    ],
    'viewParams' => [
        'searchModel' => $searchModel,
        'isAdmin' => $isAdmin,
    ],
    'layout' => $this->blocks['listBracketTableTemplate'],
    'pager' => [
        'class' => LinkPager::class,
        'options' => [
            'class' => 'pagination',
            'tag' => 'div'
        ],
        'maxButtonCount' => 5,
        'internalLinksWrapperClass' => 'pagination-list',
        'pageCssClass' => '',
        'externalLinksTag' => false,
        'linkContainerOptions' => [
            'tag' => 'li',
            'class' => 'pagination-item',
        ],
        'linkOptions' => [
            'class' => 'pagination-link btn btn--dark',
            'data-pjax' => '1'
        ],
        'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => ''],
        'disabledPageCssClass' => 'disabled',
        'prevPageCssClass' => 'pagination-btn btn btn--dark',
        'nextPageCssClass' => 'pagination-btn btn btn--dark',
        'activePageCssClass' => 'active',
        'nextPageLabel' => 'next',
        'prevPageLabel' => 'prev',
    ],

]);

?>

<?php Pjax::begin([
    'id' => 'bracket-table',
    'timeout' => 5000,
]) ?>
    <?= $this->render('_bracket-table_filter', [
        'model' => $model,
        'searchModel' => $searchModel,
        'isAdmin' => $isAdmin,
    ]) ?>
    <div class="filter-content filter-content--sm">
        <?= $list ?>
    </div>
    <?= $this->render('_bracket-table_filter', [
        'model' => $model,
        'searchModel' => $searchModel,
        'isAdmin' => $isAdmin,
    ]) ?>
<?php Pjax::end() ?>


<?php
$js = <<<JS
    $('.filter-content table').DataTable({
        paging: false,
        searching: false,
        select: false,
        info: false
    }); 
JS;

if ($existRows) $this->registerJs($js);