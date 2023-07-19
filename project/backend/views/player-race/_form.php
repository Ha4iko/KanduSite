<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\FileInput;
use common\models\PlayerRace;

/* @var $this yii\web\View */
/* @var $model PlayerRace */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="player-race-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->dropDownList(PlayerRace::getGenderLabels()) ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'avatar')->widget(FileInput::className(), [
                'model' => $model,
                'attribute' => 'avatar',
                'showLabel' => false
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
