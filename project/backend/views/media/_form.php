<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Media;

/* @var $this yii\web\View */
/* @var $model Media */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="media-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'slug')->textInput() ?>

    <?= $form->field($model, 'content')->textarea() ?>

    <?= $form->field($model, 'is_text')->checkbox() ?>

    <?= $form->field($model, 'is_video')->checkbox() ?>

    <?= $form->field($model, 'bg_image')->textInput() ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
