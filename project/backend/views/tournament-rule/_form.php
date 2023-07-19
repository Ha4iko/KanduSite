<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Tournament;

/* @var $this yii\web\View */
/* @var $model common\models\TournamentRule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tournament-rule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tournament_id')->dropDownList(Tournament::getDropDownList('Выберите...')) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
