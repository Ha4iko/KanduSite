<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PlayerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Players';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="player-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Player', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'nick',
            'class_id',
            'race_id',
            'faction_id',
            //'world_id',
            //'played',
            //'wins',
            //'defeats',
            //'winrate',
            //'bonus_points',
            //'all_points',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
