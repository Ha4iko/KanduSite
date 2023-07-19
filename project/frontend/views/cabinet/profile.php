<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model \frontend\models\CabinetProfileForm */

$this->title = 'Profile';


$imgSrc = $imgDefault = IMG_ROOT . '/logo-big.png';
if ($model->avatar) {
    $imgSrc = $model->avatar;
}
$imgIsDefault = $imgSrc === $imgDefault;
?>
<main class="main">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <section class="section section--head">
        <div class="section-bg">
            <div class="section-bg__overlay"><span></span><span></span><span></span></div>
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg15.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="container">
                <div class="section-title">
                    <h1 class="h2">settings <span>/ profile</span></h1>
                </div>
            </div>
        </div>
    </section>

    <?= $this->render('_settings_bar') ?>

    <section class="section section--main section--sm">
        <div class="section-inner">
            <div class="container--sm">
                <div class="content-block">
                    <div class="a-profile">
                        <div class="a-profile-picture js-profile-picture">
                            <div class="a-profile-picture__media">
                                <div class="a-profile-picture__<?= $imgIsDefault ? 'img' : 'bg' ?>">
                                    <img src="<?= $imgSrc ?>" alt="">
                                </div>
                            </div>
                            <div class="a-profile-picture__control">
                                <label class="a-profile-picture__btn" for="profilePicture">
                                    <div class="btn btn--sm">change profile’s picture</div>
                                    <input class="a-profile-picture__field" type="file" name="<?= $model->formName() ?>[avatar]" id="profilePicture">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-block">
                    <div class="control">
                        <div class="control-side">
                            <div class="prop"><?= Html::encode($model->getAttributeLabel('username')) ?> <span>*</span></div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field <?= $model->hasErrors('username') ? 'error' : '' ?>">
                                    <?= Html::activeTextInput($model, 'username', [
                                        'placeholder' => 'enter nickname',
                                        'class' => 'field'
                                    ]) ?>
                                    <?= Html::error($model, 'username', ['class' => 'field-error']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control">
                        <div class="control-side">
                            <div class="prop"><?= Html::encode($model->getAttributeLabel('email')) ?> <span>*</span></div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field <?= $model->hasErrors('email') ? 'error' : '' ?>">
                                    <?= Html::activeTextInput($model, 'email', [
                                        'placeholder' => 'enter email',
                                        'class' => 'field'
                                    ]) ?>
                                    <?= Html::error($model, 'email', ['class' => 'field-error']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="a-footer">
                    <button class="btn" type="submit">save</button>
                    <a class="btn" href="<?= Yii::$app->request->url ?>">cancel</a>
                </div>
            </div>
        </div>
    </section>
    <?php $form->end(); ?>
</main>
