<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Tournament;
use yii\web\View;
use common\models\TournamentMedia;
use yii\helpers\ArrayHelper;

/* @var $this View */
/* @var $model Tournament */

$this->title = 'Media | ' . Html::encode($model->title);

$tournamentMedia = [];
foreach ($model->tournamentMediaNotEmpty as $tMedia) {
    if ($tMedia->videoData['type'] == TournamentMedia::TYPE_UNKNOWN) continue;

    $tournamentMedia[] = $tMedia;
}

?>
<?php if (!isset(Yii::$app->params['preview']) && Yii::$app->user->can('updateTournament', ['tournamentId' => $model->id])) : ?>
<div class="filter filter--admin js-scroll">
    <div class="filter-main">
        <div class="filter-wrap">
            <div class="filter-inner">
                <div class="filter-btns">
                    <div class="filter-btn">
                        <button class="btn btn--sm js-ajax-popup"
                                data-url="<?= Url::to(['/tournament-media/update', 'id' => $model->id]) ?>">
                            <?= !count($tournamentMedia) ? 'Add' : 'Edit' ?> media
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="filter-content">
<?php endif; ?>



<?php if (empty($tournamentMedia)) : ?>
    <?= $this->render('_list_empty', ['renderInPlace' => true]) ?>
<?php else : ?>
    <?php foreach ($tournamentMedia as $media) :
        $videoData = $media->videoData;
    ?>
        <div class="mb">
            <div class="iframe">
                <?php if ($videoData['type'] == TournamentMedia::TYPE_YOUTUBE) : ?>
                    <iframe src="https://www.youtube.com/embed/<?= $videoData['video_id'] ?>" title="YouTube video player"
                            frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                <?php endif; ?>
                <?php if ($videoData['type'] == TournamentMedia::TYPE_TWITCH) : ?>
                    <iframe src="https://player.twitch.tv/?video=<?= $videoData['video_id'] ?>&parent=<?= ArrayHelper::getValue(Yii::$app->params, 'settings.twitch_domain') ?>" title="Twitch video player"
                            frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            width="100%" height="100%" allowfullscreen></iframe>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>



<?php if (!isset(Yii::$app->params['preview']) && Yii::$app->user->can('updateTournament', ['tournamentId' => $model->id])) : ?>
</div>
<div class="filter filter--admin js-scroll">
    <div class="filter-main">
        <div class="filter-wrap">
            <div class="filter-inner">
                <div class="filter-btn">
                    <button class="btn btn--sm js-ajax-popup"
                            data-url="<?= Url::to(['/tournament-media/update', 'id' => $model->id]) ?>">
                        <?= !count($tournamentMedia) ? 'Add' : 'Edit' ?> media
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>




<?= $this->render('_tournament_share') ?>
