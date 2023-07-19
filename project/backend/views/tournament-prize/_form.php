<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Tournament;
use common\helpers\DataTransformHelper;
use common\models\TournamentPrize;

/* @var $this yii\web\View */
/* @var $model TournamentPrize */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tournament-prize-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tournament_id')->dropDownList(
            DataTransformHelper::getList(Tournament::class, 'title', 'id', 'Выберите..')
    ) ?>

    <?= $form->field($model, 'type_id')->dropDownList(
        TournamentPrize::getTypeLabels()
    ) ?>

    <?= $form->field($model, 'money')->textInput() ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
