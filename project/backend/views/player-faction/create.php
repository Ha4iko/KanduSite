<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlayerFaction */

$this->title = 'Create Player Faction';
$this->params['breadcrumbs'][] = ['label' => 'Player Factions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="player-faction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
