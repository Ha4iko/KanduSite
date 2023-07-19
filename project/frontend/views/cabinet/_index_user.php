<?php

use frontend\models\search\UserSearch;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this View */
/* @var $model UserSearch */


?>
<tr>
    <td>
        <div class="table-player">
            <div class="table-player__avatar">
                <img src="<?= IMG_ROOT ?>/avatar1.jpg" alt=""/>
            </div>
            <div class="table-player__name">
                <?= Html::encode($model->username) ?>
            </div>
        </div>
    </td>
    <td class="table-col--left">
        <div class="h6">
            <?= $model->maxRole ?>
        </div>
    </td>
    <td>
        <div class="h6">
            <?= $model->tournamentsCount ?>
        </div>
    </td>
    <td class="table-col--right">
        <div class="h6">
            <?= ($model->maxRole == 'Superadmin' && $model->username == 'root')
                ? 'long time ago'
                : Yii::$app->formatter->asDate($model->created_at, 'php:d.m.Y') ?>
        </div>
    </td>
    <td class="table-col--right">
        <?php if ($model->maxRole != 'Superadmin') : ?>
        <div class="btns btns--sm">
            <?php if (Yii::$app->user->can('updateUser')) : ?>
                <button class="btn btn--sm js-ajax-popup"
                        data-url="<?= Url::to(['cabinet/update', 'id' => $model->id]) ?>">
                    edit
                </button>
            <?php endif; ?>

            <?php if (Yii::$app->user->can('deleteUser')) : ?>
                <button class="btn btn--sm js-ajax-popup"
                        data-url="<?= Url::to(['cabinet/delete', 'id' => $model->id]) ?>">
                    delete
                </button>
            <?php endif; ?>
        </div>
        <?php endif ?>
    </td>
</tr>
