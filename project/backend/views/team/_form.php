<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Player;
use kartik\select2\Select2;
use common\helpers\DataTransformHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Team */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="team-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'players')->widget(Select2::classname(), [
        'data' => DataTransformHelper::getList(Player::class, 'nick'),
        'language' => 'ru',
        'options' => [
            'multiple' => true,
            'placeholder' => 'Игроки..'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
