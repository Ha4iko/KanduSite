<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Tournament;
use yii\web\View;

/* @var $this View */
/* @var $model Tournament */

$this->title = 'Rules | ' . Html::encode($model->title);

$tournamentRules = $model->tournamentRulesNotEmpty;

?>
<?php if (!isset(Yii::$app->params['preview']) && Yii::$app->user->can('updateTournament', ['tournamentId' => $model->id])) : ?>
<div class="filter filter--admin js-scroll">
    <div class="filter-main">
        <div class="filter-wrap">
            <div class="filter-inner">
                <div class="filter-btns">
                    <div class="filter-btn">
                        <button class="btn btn--sm js-ajax-popup"
                                data-url="<?= Url::to(['rule/update', 'id' => $model->id]) ?>">
                            <?= !count($tournamentRules) ? 'Add' : 'Edit' ?> rules
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="filter-content">
<?php endif; ?>



<?php if (empty($tournamentRules)) : ?>
    <?= $this->render('_list_empty', ['renderInPlace' => true]) ?>
<?php else : ?>
    <div class="rules">
        <?php foreach ($tournamentRules as $rule) : ?>
            <div class="rules-item">
                <div class="rules-item__title h6">
                    <?= Html::encode($rule->title) ?>
                </div>
                <div class="rules-item__text">
                    <p><?= nl2br(Html::encode($rule->description)) ?></p>
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
                <div class="filter-btn">
                    <button class="btn btn--sm js-ajax-popup" data-url="<?= Url::to(['rule/update', 'id' => $model->id]) ?>">edit rules</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<?= $this->render('_tournament_share') ?>


