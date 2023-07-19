<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlayerClass */

$this->title = 'Create Player Class';
$this->params['breadcrumbs'][] = ['label' => 'Player Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="player-class-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
