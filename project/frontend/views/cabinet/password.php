<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model \frontend\models\CabinetProfileForm */

$this->title = 'Password';

?>
<main class="main">
    <?php $form = ActiveForm::begin(); ?>
    <section class="section section--head">
        <div class="section-bg">
            <div class="section-bg__overlay"><span></span><span></span><span></span></div>
            <div class="section-bg__img"
                 style="background-image: url(<?= IMG_ROOT ?>/bg15.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="container">
                <div class="section-title">
                    <h1 class="h2">settings <span>/ password</span></h1>
                </div>
            </div>
        </div>
    </section>

    <?= $this->render('_settings_bar') ?>

    <section class="section section--main section--sm">
        <div class="section-inner">
            <div class="container--sm">
                <div class="content-block">
                    <div class="control">
                        <div class="control-side">
                            <div class="prop"><?= Html::encode($model->getAttributeLabel('password_old')) ?> <span>*</span></div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field <?= $model->hasErrors('password_old') ? 'error' : '' ?>">
                                    <div class="password">
                                        <?= Html::activePasswordInput($model, 'password_old', [
                                            'placeholder' => 'enter old password',
                                            'class' => 'field'
                                        ]) ?>
                                        <div class="password-btn">
                                            <div class="password-btn__icon password-btn__icon--visible">
                                                <svg class="icon">
                                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-pass-visible"></use>
                                                </svg>
                                            </div>
                                            <div class="password-btn__icon password-btn__icon--hidden">
                                                <svg class="icon">
                                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-pass-hidden"></use>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <?= Html::error($model, 'password_old', ['class' => 'field-error']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control">
                        <div class="control-side">
                            <div class="prop"><?= Html::encode($model->getAttributeLabel('password')) ?> <span>*</span></div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field <?= $model->hasErrors('password') ? 'error' : '' ?>">
                                    <div class="password">
                                        <?= Html::activePasswordInput($model, 'password', [
                                            'placeholder' => 'enter new password',
                                            'class' => 'field'
                                        ]) ?>
                                        <div class="password-btn">
                                            <div class="password-btn__icon password-btn__icon--visible">
                                                <svg class="icon">
                                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-pass-visible"></use>
                                                </svg>
                                            </div>
                                            <div class="password-btn__icon password-btn__icon--hidden">
                                                <svg class="icon">
                                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-pass-hidden"></use>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <?= Html::error($model, 'password', ['class' => 'field-error']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control">
                        <div class="control-side">
                            <div class="prop"><?= Html::encode($model->getAttributeLabel('password_confirm')) ?> <span>*</span></div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field <?= $model->hasErrors('password_confirm') ? 'error' : '' ?>">
                                    <div class="password">
                                        <?= Html::activePasswordInput($model, 'password_confirm', [
                                            'placeholder' => 'enter new password again',
                                            'class' => 'field'
                                        ]) ?>
                                        <div class="password-btn">
                                            <div class="password-btn__icon password-btn__icon--visible">
                                                <svg class="icon">
                                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-pass-visible"></use>
                                                </svg>
                                            </div>
                                            <div class="password-btn__icon password-btn__icon--hidden">
                                                <svg class="icon">
                                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-pass-hidden"></use>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <?= Html::error($model, 'password_confirm', ['class' => 'field-error']) ?>
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
