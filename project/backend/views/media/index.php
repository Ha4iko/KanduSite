<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MediaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'All Media';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tournament-rule-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Media', ['create'], ['class' => 'btn btn-success']) ?>
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
                'class' => \yii\grid\ActionColumn::class,
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>


</div>
