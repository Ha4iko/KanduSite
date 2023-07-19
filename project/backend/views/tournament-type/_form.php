<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TournamentType */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="tournament-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'players_in_team')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'team_mode')->dropDownList([
        0 => 'Solo',
        1 => 'Team',
    ]) ?>

    <?= ''//$form->field($model, 'bsg')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
