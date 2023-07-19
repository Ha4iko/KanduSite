<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Team;
use frontend\models\Player;
use frontend\models\Tournament;

/* @var $this \yii\web\View */
/* @var $model array */
/* @var $adminMode boolean */

$tournament = Tournament::findOne($model['tournament_id']);
if ($model['rel_type'] == 'team') {
    $isTeam = true;
    $team = Team::findOne($model['team_id']);
    $player = null;
    $participantName = is_object($team) ? $team->name : 'Name';
    $participantDesc = 'team';
} else {
    $isTeam = false;
    $team = Team::findOne($model['team_id']);
    $player = Player::findOne($model['player_id']);;
    $participantName = is_object($player) ? $player->nick : 'Nick';
    $participantDesc = is_object($player) ? $player->getClassName($tournament->id) : 'class';
    $participantAvatar = is_object($player) ? $player->getAvatar($tournament->id) : '';
}
if (is_object($tournament)) :
?>
<div class="champs-item" data-pjax="0">
    <a class="champ" href="<?= Url::to(['/tournament/participants', 'slug' => $tournament->slug]) ?>" data-pjax="0">
        <div class="champ-participant">
            <div class="participant">
                <?php if (!$isTeam) : ?>
                <div class="participant-avatar">
                    <picture>
                        <source srcset="<?= $participantAvatar ?>" type="images/webp"/>
                        <source srcset="<?= $participantAvatar ?>"/>
                        <img src="<?= $participantAvatar ?>" alt=""/>
                    </picture>
                </div>
                <?php endif; ?>
                <div class="participant-content">
                    <div class="participant-title h5"><?= Html::encode($participantName) ?></div>
                    <div class="participant-prop <?= $isTeam ? 'prop' : '' ?>">
                        <?= Html::encode($participantDesc) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="champ-main">
            <div class="champ-media">
                <picture>
                    <!--source srcset="<?= IMG_ROOT ?>/tourney1.webp" type="<?= IMG_ROOT ?>/webp"/-->
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
                <div class="champ-prize h6">
                    <?= Html::encode($tournament->pool_custom) ?>
                </div>
            </div>
        </div>
    </a>
</div>
<?php endif; ?>