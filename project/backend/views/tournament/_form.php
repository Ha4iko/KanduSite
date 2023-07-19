<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\TournamentType;
use common\models\User;
use common\models\Team;
use common\models\Player;
use kartik\select2\Select2;
use common\widgets\FileInput;
use common\helpers\DataTransformHelper;
use common\models\Tournament;
use common\models\Language;

/* @var $this yii\web\View */
/* @var $model common\models\Tournament */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="tournament-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'teams')->widget(Select2::classname(), [
        'data' => DataTransformHelper::getList(Team::class, 'name', 'id', 'Выберите..'),
        'language' => 'ru',
        'options' => [
            'multiple' => true,
            'placeholder' => 'Команды..'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'players')->widget(Select2::classname(), [
        'data' => DataTransformHelper::getList(Player::class, 'nick', 'id', 'Выберите..'),
        'language' => 'ru',
        'options' => [
            'multiple' => true,
            'placeholder' => 'Игроки..'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'status')->dropDownList(Tournament::getStatusLabels()) ?>

    <?= $form->field($model, 'pool')->textInput() ?>

    <?= $form->field($model, 'date')->input('date') ?>

    <?= $form->field($model, 'timeFormatted')->input('time') ?>

    <?= $form->field($model, 'type_id')->dropDownList(
        DataTransformHelper::getList(TournamentType::class, 'name', 'id', 'Выберите..')
    ) ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'bg_image')->widget(FileInput::className(), [
                'model' => $model,
                'attribute' => 'bg_image',
                'showLabel' => false
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'organizer_id')->dropDownList(
        DataTransformHelper::getList(User::class, 'username', 'id', 'Выберите..')
    ) ?>

    <?= $form->field($model, 'language_id')->dropDownList(
        DataTransformHelper::getList(Language::class, 'name', 'id', 'Выберите..')
    ) ?>

    <?= $form->field($model, 'time_zone')->dropDownList(
        Yii::$app->params['timeZones']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
