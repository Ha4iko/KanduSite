<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Tournament;
use yii\web\View;

/* @var $this View */
/* @var $model Tournament */

$this->title = 'Participants | ' . Html::encode($model->title);

$participants = $model->getParticipantsData();

?>

<?php if (!isset(Yii::$app->params['preview']) && Yii::$app->user->can('updateTournament', ['tournamentId' => $model->id])) : ?>
<div class="filter filter--admin js-scroll">
    <div class="filter-main">
        <div class="filter-wrap">
            <div class="filter-inner">
                <div class="filter-btns">
                    <div class="filter-btn">
                        <button class="btn btn--sm js-ajax-popup"
                                data-url="<?= Url::to(['participant/update', 'id' => $model->id]) ?>">
                            <?= !count($participants) ? 'Add' : 'Edit' ?> participants
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="filter-content">
<?php endif; ?>



<?php if (!$participants) : ?>
    <?= $this->render('_list_empty', ['renderInPlace' => true]) ?>
<?php else : ?>
    <div class="participants">
        <?php foreach ($participants as $participant) : ?>
        <div class="participant">
            <div class="participant-avatar">
                <picture>
                    <source srcset="<?= $participant['player']->getAvatar($model->id) ?>"/>
                    <img src="<?= $participant['player']->getAvatar($model->id) ?>" alt=""/>
                </picture>
            </div>
            <div class="participant-content">
                <div class="participant-title h6" style="color: <?= $participant['player']->getClassColor($model->id) ?> !important;">
                    <?php if ($participant['player']->external_link): ?>
                        <a href="<?= $participant['player']->external_link ?>" class="no-decor" target="_blank" style="color: <?= $participant['player']->getClassColor($model->id) ?> !important;">
                            <?= Html::encode($participant['player']->nick) ?>
                        </a>
                    <?php else: ?>
                        <?= Html::encode($participant['player']->nick) ?>
                    <?php endif; ?>
                </div>
                <div class="participant-prop">
                    <?= Html::encode($participant['params']->className) ?>
                </div>
            </div>
        </div>
        <?php $arry_test[] = ['name' =>[$participant['player']->nick], 'class' => [$participant['params']->className], 'color' => [$participant['player']->getClassColor($model->id)], 'avatar' => [$participant['player']->getAvatar($model->id)]] ?>
        <?php endforeach;
         $nameJsom = Html::encode($model->title)?>
        <?php $file = 'partisipants.json';
        file_put_contents($file, json_encode($arry_test)); ?>      
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
                        <button class="btn btn--sm js-ajax-popup" data-url="<?= Url::to(['participant/update', 'id' => $model->id]) ?>">edit participants</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<?= $this->render('_tournament_share') ?>
