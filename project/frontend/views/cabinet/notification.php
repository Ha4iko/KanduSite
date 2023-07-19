<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model \frontend\models\CabinetProfileForm */

$this->title = 'Notification';

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
                    <h1 class="h2">settings <span>/ notification</span></h1>
                </div>
            </div>
        </div>
    </section>

    <?= $this->render('_settings_bar') ?>

    <section class="section section--main section--sm">
        <div class="section-inner">
            <div class="container--sm">
                <div class="content-block">
                    <div class="a-notification">
                        <div class="a-notification-checkbox">
                            <div class="checkbox checkbox--toggler">
                                <label class="checkbox-label" for="notificationCheck1">
                                    <input class="checkbox-input" type="checkbox" name="CabinetNotificationForm[mark_courses]"
                                           id="notificationCheck1" <?= $model->mark_courses ? 'checked="checked"' : '' ?> />
                                    <div class="checkbox-content">
                                        <div class="checkbox-style"></div>
                                        <div class="checkbox-text h6" data-text-in="enabled" data-text-out="disabled">
                                            <?= $model->mark_courses ? 'enabled' : 'disabled' ?>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="a-notification-prop prop"><?= $model->getAttributeLabel('mark_courses') ?></div>
                    </div>
                    <div class="a-notification">
                        <div class="a-notification-checkbox">
                            <div class="checkbox checkbox--toggler">
                                <label class="checkbox-label" for="notificationCheck2">
                                    <input class="checkbox-input" type="checkbox" name="CabinetNotificationForm[mark_discounts]"
                                           id="notificationCheck2" <?= $model->mark_discounts ? 'checked="checked"' : '' ?> />
                                    <div class="checkbox-content">
                                        <div class="checkbox-style"></div>
                                        <div class="checkbox-text h6" data-text-in="enabled" data-text-out="disabled">
                                            <?= $model->mark_discounts ? 'enabled' : 'disabled' ?>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="a-notification-prop prop"><?= $model->getAttributeLabel('mark_discounts') ?></div>
                    </div>
                    <div class="a-notification">
                        <div class="a-notification-checkbox">
                            <div class="checkbox checkbox--toggler">
                                <label class="checkbox-label" for="notificationCheck3">
                                    <input class="checkbox-input" type="checkbox" name="CabinetNotificationForm[mark_certificate]"
                                           id="notificationCheck3" <?= $model->mark_certificate ? 'checked="checked"' : '' ?> />
                                    <div class="checkbox-content">
                                        <div class="checkbox-style"></div>
                                        <div class="checkbox-text h6" data-text-in="enabled" data-text-out="disabled">
                                            <?= $model->mark_certificate ? 'enabled' : 'disabled' ?>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="a-notification-prop prop"><?= $model->getAttributeLabel('mark_certificate') ?></div>
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
