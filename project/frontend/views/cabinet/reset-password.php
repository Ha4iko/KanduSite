<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model \frontend\models\ResetPasswordForm */

$this->title = 'Reset password';

?>
<main class="main">
    <section class="section section--head">
        <div class="section-bg">
            <div class="section-bg__overlay"><span></span><span></span><span></span></div>
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg7.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="container">
                <div class="section-title">
                    <h1 class="h2">Reset password</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="section section--main section--sm">
        <div class="section-bg">
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg5.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="container--sm">
                <div class="thanks">
                    <div class="mb">


                        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                        <div class="content-block">
                            <div class="control">
                                <div class="control-side">

                                </div>
                                <div class="control-content">
                                    Please choose your new password:
                                </div>
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
                        </div>
                        <div class="a-footer">
                            <button class="btn" type="submit">Reset</button>
                        </div>


                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
