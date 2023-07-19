<?php

/** @var $this \yii\web\View */
/** @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\Pjax;
use \common\models\LoginForm;

$model = $model ?? new LoginForm();

?>

<div class="popup" id="adminLogin">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]) ?>
                <?= Html::beginForm(['/cabinet/login'], 'post', ['data-pjax' => 1]) ?>
                    <div class="popup-head">
                        <div class="popup-close js-popup-close">
                            <a class="link-back" href="#">
                                <svg class="icon">
                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                </svg>close
                            </a>
                        </div>
                        <div class="popup-title h3">login</div>
                    </div>
                    <div class="popup-content">
                        <div class="content-block">
                            <div class="controls">

                                <div class="control">
                                    <div class="control-side">
                                        <div class="prop">
                                            <?= Html::encode($model->getAttributeLabel('email')) ?> <span>*</span>
                                        </div>
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

                                <div class="control">
                                    <div class="control-side">
                                        <div class="prop">
                                            <?= Html::encode($model->getAttributeLabel('password')) ?> <span>*</span>
                                        </div>
                                    </div>
                                    <div class="control-content">
                                        <div class="control-fields">
                                            <div class="control-field <?= $model->hasErrors('email') ? 'error' : '' ?>">
                                                <div class="password">
                                                    <?= Html::activePasswordInput($model, 'password', [
                                                        'placeholder' => 'enter password',
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
                                        <div class="prop">

                                        </div>
                                    </div>
                                    <div class="control-content">
                                        <a class="js-popup-change" href="#" data-popup-show="forgotPassword" data-popup-hide="adminLogin">
                                            Forgot your password?
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="a-footer a-footer--start">
                        <button class="btn account-login" type="submit">login</button>
                        <a class="btn js-popup-close" href="#">cancel</a>
                    </div>
                <?= Html::endForm() ?>
            <?php Pjax::end() ?>
        </div>
    </div>
</div>
