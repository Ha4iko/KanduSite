<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlayerRace */

$this->title = 'Update Player Race: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Player Races', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="player-race-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
