<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlayerWorld */

$this->title = 'Create Player World';
$this->params['breadcrumbs'][] = ['label' => 'Player Worlds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="player-world-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
