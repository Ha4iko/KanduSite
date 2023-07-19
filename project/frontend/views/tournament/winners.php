<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Tournament;
use frontend\models\Player;
use common\models\TournamentPrize;
use yii\web\View;

/* @var $this View */
/* @var $model Tournament */

$this->title = 'Winners | ' . Html::encode($model->title);

$winnersStandard = $model->getWinnersPlayersStandard();
$winnersSpecial = $model->getWinnersPlayersDynamic(TournamentPrize::TYPE_SPECIAL);
$winnersSecondary = $model->getWinnersPlayersDynamic(TournamentPrize::TYPE_SECONDARY);
$winnersNo = $model->getPlayersWithoutRewards();

$winnersIsEmpty = !$winnersStandard && !$winnersSpecial && !$winnersSecondary;
$contentIsEmpty = !$winnersStandard && !$winnersSpecial && !$winnersSecondary && !$winnersNo;

$pictureKey = 4;
?>
<?php if (!isset(Yii::$app->params['preview']) && Yii::$app->user->can('updateTournament', ['tournamentId' => $model->id])) : ?>
<div class="filter filter--admin js-scroll">
    <div class="filter-main">
        <div class="filter-wrap">
            <div class="filter-inner">
                <div class="filter-btns">
                    <div class="filter-btn">
                        <button class="btn btn--sm js-ajax-popup" data-url="<?= Url::to(['winner/update', 'id' => $model->id]) ?>">
                            <?= $winnersIsEmpty ? 'Insert' : 'Edit' ?> winners
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="filter-content">
<?php endif; ?>



<?php if ($contentIsEmpty) : ?>
    <?= $this->render('_list_empty', ['renderInPlace' => true]) ?>
<?php else : ?>
    <div class="content">
        <?php if ($winnersStandard) : ?>
        <div class="content-block">
            <h6 class="content-block__title">Standard Prizes</h6>
            <div class="prizes">
                <?php foreach ($winnersStandard as $rewardId => $reward) : ?>
                    <div class="prize">
                        <picture>
                            <source srcset="<?= IMG_ROOT ?>/prize<?= $rewardId ?>.webp" type="<?= IMG_ROOT ?>/webp"/>
                            <source srcset="<?= IMG_ROOT ?>/prize<?= $rewardId ?>.jpg"/>
                            <img class="prize-img" src="<?= IMG_ROOT ?>/prize<?= $rewardId ?>.jpg" alt=""/>
                        </picture>
                        <div class="prize-inner">
                            <div class="prize-participant">
                                <div class="participant">
                                    <div class="participant-avatar">
                                        <picture>
                                            <source srcset="<?= $reward['owner']->getAvatar($model->id) ?>" type="<?= IMG_ROOT ?>/webp"/>
                                            <source srcset="<?= $reward['owner']->getAvatar($model->id) ?>"/>
                                            <img src="<?= $reward['owner']->getAvatar($model->id) ?>" alt=""/>
                                        </picture>
                                    </div>
                                    <div class="participant-content">
                                        <div class="participant-title h5" style="color: <?= Html::encode($reward['owner']->getClassColor($model->id)) ?> !important;">
                                            <?php if ($reward['owner']->external_link): ?>
                                                <a href="<?= $reward['owner']->external_link ?>" class="no-decor" target="_blank" style="color: <?= $reward['owner']->getClassColor($model->id) ?> !important;">
                                                    <?= Html::encode($reward['owner']->nick) ?>
                                                </a>
                                            <?php else: ?>
                                                <?= Html::encode($reward['owner']->nick) ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="participant-prop">
                                            <?= Html::encode($reward['owner']->getClassName($model->id)) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="prize-content">
                                <div class="h3 prize-value"><?= Html::encode($reward['reward']) ?></div>
                                <div class="h6 prize-place"><?= Html::encode($reward['description']) ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($winnersSpecial) : ?>
        <div class="content-block">
            <h6 class="content-block__title">special prizes</h6>
            <div class="prizes">
                <?php foreach ($winnersSpecial as $rewardKey => $rewardRaw) :
                    $pictureKey++;
                    $reward = $rewardRaw['reward']; /* @var TournamentPrize $reward */
                    $player = $rewardRaw['owner']; /* @var Player $player */
                ?>
                <div class="prize">
                    <picture>
                        <source srcset="<?= IMG_ROOT ?>/prize<?= $pictureKey ?>.webp" type="<?= IMG_ROOT ?>/webp"/>
                        <source srcset="<?= IMG_ROOT ?>/prize<?= $pictureKey ?>.jpg"/>
                        <img class="prize-img" src="<?= IMG_ROOT ?>/prize<?= $pictureKey ?>.jpg" alt=""/>
                    </picture>
                    <div class="prize-inner">
                        <div class="prize-participant">
                            <div class="participant">
                                <div class="participant-avatar">
                                    <picture>
                                        <source srcset="<?= $player->getAvatar($model->id) ?>" type="<?= IMG_ROOT ?>/webp"/>
                                        <source srcset="<?= $player->getAvatar($model->id) ?>"/>
                                        <img src="<?= $player->getAvatar($model->id) ?>" alt=""/>
                                    </picture>
                                </div>
                                <div class="participant-content">
                                    <div class="participant-title h5" style="color: <?= Html::encode($reward['owner']->getClassColor($model->id)) ?> !important;">
                                        <?php if ($player->external_link): ?>
                                            <a href="<?= $player->external_link ?>" class="no-decor" target="_blank" style="color: <?= Html::encode($reward['owner']->getClassColor($model->id)) ?> !important;">
                                                <?= Html::encode($player->nick) ?>
                                            </a>
                                        <?php else: ?>
                                            <?= Html::encode($player->nick) ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="participant-prop">
                                        <?= Html::encode($player->getClassName($model->id)) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="prize-content">
                            <div class="h3 prize-value"><?= Html::encode($reward->money) ?></div>
                            <div class="h6 prize-place"><?= Html::encode($reward->description) ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($winnersSecondary) : ?>
        <div class="content-block">
            <h6 class="content-block__title">secondary prizes</h6>
            <div class="prizes">
                <?php foreach ($winnersSecondary as $rewardKey => $rewardRaw) :
                    $reward = $rewardRaw['reward']; /* @var TournamentPrize $reward */
                    $player = $rewardRaw['owner']; /* @var Player $player */
                ?>
                <div class="prize">
                    <div class="prize-inner">
                        <div class="prize-participant">
                            <div class="participant">
                                <div class="participant-avatar">
                                    <picture>
                                        <source srcset="<?= $player->getAvatar($model->id) ?>" type="<?= IMG_ROOT ?>/webp"/>
                                        <source srcset="<?= $player->getAvatar($model->id) ?>"/>
                                        <img src="<?= $player->getAvatar($model->id) ?>" alt=""/>
                                    </picture>
                                </div>
                                <div class="participant-content">
                                    <div class="participant-title h5" style="color: <?= $player->getClassColor($model->id) ?> !important;">
                                        <?php if ($player->external_link): ?>
                                            <a href="<?= $player->external_link ?>" class="no-decor" target="_blank" style="color: <?= $player->getClassColor($model->id) ?> !important;">
                                                <?= Html::encode($player->nick) ?>
                                            </a>
                                        <?php else: ?>
                                            <?= Html::encode($player->nick) ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="participant-prop">
                                        <?= Html::encode($player->getClassName($model->id)) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="prize-content">
                            <div class="h3 prize-value"><?= Html::encode($reward->money) ?></div>
                            <div class="h6 prize-place"><?= Html::encode($reward->description) ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($winnersNo) : ?>
        <div class="content-block">
            <h6 class="content-block__title">other participants</h6>
            <div class="participants">
                <?php foreach ($winnersNo as $keyNotWinner => $playerNotWinner) : ?>
                <div class="participant">
                    <div class="participant-avatar">
                        <picture>
                            <source srcset="<?= $playerNotWinner->getAvatar($model->id) ?>" type="<?= IMG_ROOT ?>/webp"/>
                            <source srcset="<?= $playerNotWinner->getAvatar($model->id) ?>"/>
                            <img src="<?= $playerNotWinner->getAvatar($model->id) ?>" alt=""/>
                        </picture>
                    </div>
                    <div class="participant-content">
                        <div class="participant-title h6" style="color: <?= $playerNotWinner->getClassColor($model->id) ?> !important;">
                            <?php if ($playerNotWinner->external_link): ?>
                                <a href="<?= $playerNotWinner->external_link ?>" class="no-decor" target="_blank" style="color: <?= $playerNotWinner->getClassColor($model->id) ?> !important;">
                                    <?= Html::encode($playerNotWinner->nick) ?>
                                </a>
                            <?php else: ?>
                                <?= Html::encode($playerNotWinner->nick) ?>
                            <?php endif; ?>
                        </div>
                        <div class="participant-prop">
                            <?= Html::encode($playerNotWinner->getClassName($model->id)) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
<?php endif; ?>




<?php if (!isset(Yii::$app->params['preview']) && Yii::$app->user->can('updateTournament', ['tournamentId' => $model->id])) : ?>
</div>
<div class="filter filter--admin js-scroll">
    <div class="filter-main">
        <div class="filter-wrap">
            <div class="filter-inner">
                <div class="filter-btns">
                    <div class="filter-btn">
                        <button class="btn btn--sm js-ajax-popup" data-url="<?= Url::to(['winner/update', 'id' => $model->id]) ?>">
                            <?= $winnersIsEmpty ? 'Insert' : 'Edit' ?> winners
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->render('_tournament_share') ?>
