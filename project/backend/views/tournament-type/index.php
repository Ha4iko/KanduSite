<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TournamentTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tournament Types';
?>
<div class="tournament-type-index">

    <h1 class="mt-3 mb-4"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tournament Type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'description',
            'players_in_team',
            [
                'attribute' => 'team_mode',
                'format' => 'raw',
                'filter' => false,
                'value' => function($model, $key, $index, $column) {
                    return $model->team_mode ? 'Team' : 'Solo';
                },
            ],
            // [
            //     'attribute' => 'bsg',
            //     'format' => 'raw',
            //     'filter' => false,
            //     'value' => function($model, $key, $index, $column) {
            //         return $model->bsg ? '+' : '';
            //     },
            // ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>


</div>
