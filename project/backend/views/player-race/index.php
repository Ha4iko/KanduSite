<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\PlayerRace;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PlayerRaceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Player Races';
$dataProvider->pagination->setPageSize(100);
?>
<div class="player-race-index">

    <h1 class="mt-3 mb-4"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Player Race', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            [
                'attribute' => 'gender',
                'format' => 'raw',
                'filter' => PlayerRace::getGenderLabels(),
                'value' => function($model, $key, $index, $column) {
                    return $model->genderLabel;
                },
            ],
            [
                'attribute' => 'avatar',
                'format' => 'raw',
                'filter' => false,
                'value' => function($model, $key, $index, $column) {
                    return $model->avatar ? Html::img($model->avatar, ['style' => 'width: 100px;']) : '';
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>


</div>
