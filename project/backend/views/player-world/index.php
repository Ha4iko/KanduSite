<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PlayerWorldSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Player Worlds';
?>
<div class="player-world-index">

    <h1 class="mt-3 mb-4"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Player World', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>


</div>
