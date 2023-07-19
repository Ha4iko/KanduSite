<?php

use yii\helpers\Url;

/* @var $this \yii\web\View */

$actionId = Yii::$app->controller->action->id;
?>
<div class="nav js-scroll">
    <div class="container--sm">
        <div class="nav-container">

            <ul class="nav-list">
                <li class="nav-item">
                    <a class="nav-link <?= $actionId == 'profile' ? 'active' : '' ?>"
                       href="<?= Url::to(['/cabinet/profile']) ?>">
                        profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $actionId == 'password' ? 'active' : '' ?>"
                       href="<?= Url::to(['/cabinet/password']) ?>">
                        password
                    </a>
                </li>
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link --><?//= $actionId == 'notification' ? 'active' : '' ?><!--"-->
<!--                       href="--><?//= Url::to(['/cabinet/notification']) ?><!--">-->
<!--                        notification-->
<!--                    </a>-->
<!--                </li>-->
                <li class="nav-item">
                    <a class="nav-link <?= $actionId == 'interface' ? 'active' : '' ?>"
                       href="<?= Url::to(['/cabinet/interface']) ?>">
                        interface
                    </a>
                </li>
            </ul>

        </div>
    </div>
</div>
