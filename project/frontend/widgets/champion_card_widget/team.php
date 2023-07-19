<?php

use yii\web\View;
use yii\helpers\Html;
use frontend\models\Team;
use frontend\models\Tournament;
use frontend\models\Player;
use yii\helpers\Url;

/* @var $this View */
/* @var $relation array */
/* @var $player Player */
/* @var $team Team|null */
/* @var $tournament Tournament */

?>
<div class="champs-item">
    <a class="champ" href="<?= Url::to(['/tournament/participants', 'slug' => $tournament->slug]) ?>" data-pjax="0">
        <div class="champ-participant">
            <div class="participant">
                <div class="participant-content">
                    <div class="participant-title h5"><?= Html::encode($team->name) ?></div>
                    <div class="participant-prop prop">team</div>
                </div>
            </div>
        </div>
        <div class="champ-main">
            <div class="champ-media">
                <picture>
                    <!--source srcset="<?= IMG_ROOT ?>/champ2.webp" type="<?= IMG_ROOT ?>/webp"/-->
                    <source srcset="<?= $tournament->getThumbnail('bg_image', 420, 120) ?>"/>
                    <img class="champ-img" src="<?= $tournament->getThumbnail('bg_image', 420, 120) ?>" alt=""/>
                </picture>
            </div>
            <div class="champ-content">
                <div class="champ-info">
                    <div class="champ-title h6"><?= Html::encode($tournament->title) ?></div>
                    <div class="prop"><?= Html::encode($tournament->typeName) ?></div>
                    <div class="prop"><?= Yii::$app->formatter->asDate($tournament->date, 'php:j F Y') ?></div>
                </div>
                <div class="champ-prize h6"><?= Html::encode($tournament->prize_one) ?></div>
            </div>
        </div>
    </a>
</div>
