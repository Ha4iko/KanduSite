<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PlayerClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Player Classes';
$dataProvider->pagination->setPageSize(100);
?>
<div class="player-class-index">

    <h1 class="mt-3 mb-4"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Player Class', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'avatar',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>


</div>
