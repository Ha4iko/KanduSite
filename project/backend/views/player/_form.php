<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\PlayerRace;
use common\models\PlayerFaction;
use common\models\PlayerWorld;
use common\models\PlayerClass;
use common\helpers\DataTransformHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Player */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="player-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nick')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'class_id')->dropDownList(
        DataTransformHelper::getList(PlayerClass::class, 'name', 'id', 'Выберите..')
    ) ?>

    <?= $form->field($model, 'race_id')->dropDownList(
        DataTransformHelper::getList(PlayerRace::class, 'name', 'id', 'Выберите..')
    ) ?>

    <?= $form->field($model, 'faction_id')->dropDownList(
        DataTransformHelper::getList(PlayerFaction::class, 'name', 'id', 'Выберите..')
    ) ?>

    <?= $form->field($model, 'world_id')->dropDownList(
        DataTransformHelper::getList(PlayerWorld::class, 'name', 'id', 'Выберите..')
    ) ?>

    <?= $form->field($model, 'played')->textInput() ?>

    <?= $form->field($model, 'wins')->textInput() ?>

    <?= $form->field($model, 'defeats')->textInput() ?>

    <?= $form->field($model, 'winrate')->textInput() ?>

    <?= $form->field($model, 'bonus_points')->textInput() ?>

    <?= $form->field($model, 'all_points')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
