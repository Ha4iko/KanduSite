<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlayerClass */

$this->title = 'Update Player Class: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Player Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="player-class-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
