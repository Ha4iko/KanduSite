<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlayerRace */

$this->title = 'Create Player Race';
$this->params['breadcrumbs'][] = ['label' => 'Player Races', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="player-race-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
