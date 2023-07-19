<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\PlayerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="player-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nick') ?>

    <?= $form->field($model, 'class_id') ?>

    <?= $form->field($model, 'race_id') ?>

    <?= $form->field($model, 'faction_id') ?>

    <?php // echo $form->field($model, 'world_id') ?>

    <?php // echo $form->field($model, 'played') ?>

    <?php // echo $form->field($model, 'wins') ?>

    <?php // echo $form->field($model, 'defeats') ?>

    <?php // echo $form->field($model, 'winrate') ?>

    <?php // echo $form->field($model, 'bonus_points') ?>

    <?php // echo $form->field($model, 'all_points') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
