<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TournamentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tournaments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tournament-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tournament', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'title',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter' => \common\models\Tournament::getStatusLabels(),
                'value' => function($model, $key, $index, $column) {
                    return $model->statusLabel;
                },
            ],
            'pool',
            [
                'attribute' => 'date',
                'format' => ['date', 'php:d.m.Y']
            ],
            [
                'attribute' => 'time',
                'format' => ['time', 'php:H:i']
            ],
            //'type_id',
            //'bg_image',
            //'organizer_id',
            //'language_id',

            [
                'class' => yii\grid\ActionColumn::class,
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>


</div>
