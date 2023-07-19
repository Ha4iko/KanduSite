<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Tournament;
use yii\web\View;

/* @var $this View */
/* @var $model Tournament */

$this->title = 'Participants | ' . Html::encode($model->title);

$participantsWithTeams = $model->getParticipantsWithTeams();
?>

<?php if (!isset(Yii::$app->params['preview']) && Yii::$app->user->can('updateTournament', ['tournamentId' => $model->id])) : ?>
<div class="filter filter--admin js-scroll">
    <div class="filter-main">
        <div class="filter-wrap">
            <div class="filter-inner">
                <div class="filter-btns">
                    <div class="filter-btn">
                        <button class="btn btn--sm js-ajax-popup"
                                data-url="<?= Url::to(['participant/update-team', 'id' => $model->id]) ?>">
                            <?= !count($participantsWithTeams) ? 'Add' : 'Edit' ?> participants
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="filter-content filter-content--sm">
<?php endif; ?>



<?php if (!$participantsWithTeams) : ?>
    <?= $this->render('_list_empty', ['renderInPlace' => true]) ?>
<?php else : ?>
    <div class="participants-teams">
        <?php foreach ($participantsWithTeams as $teamId => $participantsData) :
            $team = $participantsData['team']; /* @var $team \frontend\models\Team */
        ?>
        <div class="participants-team">
            <div class="participants">
                <div class="participants-title h6"><?= Html::encode($team->name) ?></div>
                <div class="participants">
                    <?php foreach ($participantsData['players'] as $participant) :
                        $player = $participant['player']; /* @var $player \frontend\models\Player */
                        $params = $participant['params']; /* @var $params \frontend\models\TournamentToPlayer */
                    ?>
                    <div class="participant">
                        <div class="participant-avatar">
                            <picture>
                                <?php /*<source srcset="images/champ-avatar1.webp" type="images/webp"/>*/ ?>
                                <source srcset="<?= $player->getAvatar($model->id) ?>"/>
                                <img src="<?= $player->getAvatar($model->id) ?>" alt=""/>
                            </picture>
                        </div>
                        <div class="participant-content">
                            <div class="participant-title h6" style="color: <?= $player->getClassColor($model->id) ?> !important;">
                                <?php if ($participant['player']->external_link): ?>
                                    <a href="<?= $player->external_link ?>" class="no-decor" target="_blank" style="color: <?= $player->getClassColor($model->id) ?> !important;">
                                        <?= Html::encode($player->nick) ?>
                                    </a>
                                <?php else: ?>
                                    <?= Html::encode($player->nick) ?>
                                <?php endif; ?>
                            </div>
                            <div class="participant-prop"><?= Html::encode($params->className) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
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
                        <button class="btn btn--sm js-ajax-popup" data-url="<?= Url::to(['participant/update-team', 'id' => $model->id]) ?>">edit participants</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<?= $this->render('_tournament_share') ?>
