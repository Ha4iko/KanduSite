<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs("document.getElementsByTagName('body')[0].classList.add('loading');", View::POS_BEGIN);
$this->registerJs("document.getElementsByTagName('body')[0].classList.remove('loading');", View::POS_LOAD);

?>

<div class="setting-form">

    <div class="row">
      <div class="col-lg-8">
          <?php $form = ActiveForm::begin(); ?>

          <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

          <?= ''//$form->field($model, 'description')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'value')->textarea() ?>

        <div class="form-group mt-4 pt-4 border-top">
            <?= Html::submitButton('<span class="glyphicon glyphicon-check"></span> Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

          <?php ActiveForm::end(); ?>
      </div>
    </div>


</div>
