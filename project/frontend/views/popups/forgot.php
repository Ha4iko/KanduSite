<?php

/** @var $this \yii\web\View */
/** @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\widgets\Pjax;
use \frontend\models\PasswordResetRequestForm;

$model = $model ?? new PasswordResetRequestForm();

?>

<div class="popup" id="forgotPassword">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'forgotForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]) ?>
                <?= Html::beginForm(['/cabinet/request-password-reset'], 'post', ['data-pjax' => 1]) ?>
                    <div class="popup-head">
                        <div class="popup-close js-popup-close">
                            <a class="link-back" href="#">
                                <svg class="icon">
                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                </svg>close
                            </a>
                        </div>
                        <div class="popup-title h3">reset password</div>
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
                                            <div class="control-field__hint">
                                                <div class="text--sm">
                                                    Please fill out your email. A link to reset password will be sent there.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="a-footer a-footer--start">
                        <button class="btn" type="submit">send email</button>
                        <a class="btn js-popup-close" href="#">cancel</a>
                    </div>
                <?= Html::endForm() ?>
            <?php Pjax::end() ?>
        </div>
    </div>
</div>
