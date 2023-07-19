<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $model \frontend\models\CabinetProfileForm */

$this->title = 'Interface';

?>
<main class="main">
    <?php $form = ActiveForm::begin(); ?>
    <section class="section section--head">
        <div class="section-bg">
            <div class="section-bg__overlay"><span></span><span></span><span></span></div>
            <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg15.jpg)"></div>
        </div>
        <div class="section-inner">
            <div class="container">
                <div class="section-title">
                    <h1 class="h2">settings <span>/ interface</span></h1>
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
                            <div class="prop"><?= Html::encode($model->getAttributeLabel('time_zone')) ?></div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field <?= $model->hasErrors('time_zone') ? 'error' : '' ?>">
                                    <div class="select">
                                        <div class="select-btn">
                                            <?= Html::activeDropDownList($model, 'time_zone',
                                                ArrayHelper::merge([null => 'choose time zone..'], Yii::$app->params['timeZones']),
                                                [
                                                    'data-placeholder' => 'choose time zone',
                                                    'data-style' => '2',
                                                    'class' => 'js-select'
                                                ]
                                            ) ?>
                                        </div>
                                        <div class="select-drop">
                                            <div class="close js-close">
                                                <div class="close-inner">
                                                    <div class="close-icon">
                                                        <svg class="icon">
                                                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                        </svg>
                                                    </div>
                                                    <div class="close-text">close</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?= Html::error($model, 'time_zone', ['class' => 'field-error']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control">
                        <div class="control-side">
                            <div class="prop"><?= Html::encode($model->getAttributeLabel('language_id')) ?></div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field <?= $model->hasErrors('language_id') ? 'error' : '' ?>">
                                    <div class="select"><!-- disabled -->
                                        <div class="select-btn">
                                            <?= Html::activeDropDownList($model, 'language_id',
                                                ArrayHelper::merge([null => 'choose language..'], Yii::$app->params['languages']),
                                                [
                                                    'data-placeholder' => 'choose language',
                                                    'data-style' => '2',
                                                    'class' => 'js-select'
                                                ]
                                            ) ?>
                                        </div>
                                        <div class="select-drop">
                                            <div class="close js-close">
                                                <div class="close-inner">
                                                    <div class="close-icon">
                                                        <svg class="icon">
                                                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                        </svg>
                                                    </div>
                                                    <div class="close-text">close</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?= Html::error($model, 'language_id', ['class' => 'field-error']) ?>
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
