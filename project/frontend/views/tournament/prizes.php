<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Tournament;
use yii\web\View;
use common\models\TournamentPrize;

/* @var $this View */
/* @var $model Tournament */

$this->title = 'Prizes | ' . Html::encode($model->title);

$specialPrizes = $model->getTournamentPrizesByType(TournamentPrize::TYPE_SPECIAL);
$secondaryPrizes = $model->getTournamentPrizesByType(TournamentPrize::TYPE_SECONDARY);

$existStandardPrizes = trim($model->prize_one) || trim($model->prize_two) ||
    trim($model->prize_three) || trim($model->prize_four);

$pictureKey = 4;

?>
<?php if (!isset(Yii::$app->params['preview']) && Yii::$app->user->can('updateTournament', ['tournamentId' => $model->id])) : ?>
<div class="filter filter--admin js-scroll">
    <div class="filter-main">
        <div class="filter-wrap">
            <div class="filter-inner">
                <div class="filter-btns">
                    <div class="filter-btn">
                        <button class="btn btn--sm js-ajax-popup"
                                data-url="<?= Url::to(['prize/update', 'id' => $model->id]) ?>">
                            <?= (!$existStandardPrizes && !$specialPrizes && !$secondaryPrizes) ? 'Add' : 'Edit' ?> prizes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="filter-content">
<?php endif; ?>



<?php if (!$existStandardPrizes && !$specialPrizes && !$secondaryPrizes) : ?>
    <?= $this->render('_list_empty', ['renderInPlace' => true]) ?>
<?php else : ?>
    <div class="content">

        <?php if ($existStandardPrizes) : ?>
        <div class="content-block">
            <h6 class="content-block__title">Standard Prizes</h6>
            <div class="prizes--sm">
                <?php if (trim($model->prize_one)) : ?>
                <div class="prize">
                    <picture>
                        <source srcset="<?= IMG_ROOT ?>/prize1.webp" type="<?= IMG_ROOT ?>/webp"/>
                        <source srcset="<?= IMG_ROOT ?>/prize1.jpg"/>
                        <img class="prize-img" src="<?= IMG_ROOT ?>/prize1.jpg" alt=""/>
                    </picture>
                    <div class="prize-inner">
                        <div class="prize-content">
                            <div class="h4 prize-value"><?= Html::encode($model->prize_one) ?></div>
                            <div class="h6 prize-place"><?= Html::encode($model->getAttributeLabel('prize_one')) ?></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (trim($model->prize_two)) : ?>
                <div class="prize">
                    <picture>
                        <source srcset="<?= IMG_ROOT ?>/prize2.webp" type="<?= IMG_ROOT ?>/webp"/>
                        <source srcset="<?= IMG_ROOT ?>/prize2.jpg"/>
                        <img class="prize-img" src="<?= IMG_ROOT ?>/prize2.jpg" alt=""/>
                    </picture>
                    <div class="prize-inner">
                        <div class="prize-content">
                            <div class="h4 prize-value"><?= Html::encode($model->prize_two) ?></div>
                            <div class="h6 prize-place"><?= Html::encode($model->getAttributeLabel('prize_two')) ?></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (trim($model->prize_three)) : ?>
                <div class="prize">
                    <picture>
                        <source srcset="<?= IMG_ROOT ?>/prize3.webp" type="<?= IMG_ROOT ?>/webp"/>
                        <source srcset="<?= IMG_ROOT ?>/prize3.jpg"/>
                        <img class="prize-img" src="<?= IMG_ROOT ?>/prize3.jpg" alt=""/>
                    </picture>
                    <div class="prize-inner">
                        <div class="prize-content">
                            <div class="h4 prize-value"><?= Html::encode($model->prize_three) ?></div>
                            <div class="h6 prize-place"><?= Html::encode($model->getAttributeLabel('prize_three')) ?></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (trim($model->prize_four)) : ?>
                <div class="prize">
                    <picture>
                        <source srcset="<?= IMG_ROOT ?>/prize4.webp" type="<?= IMG_ROOT ?>/webp"/>
                        <source srcset="<?= IMG_ROOT ?>/prize4.jpg"/>
                        <img class="prize-img" src="<?= IMG_ROOT ?>/prize4.jpg" alt=""/>
                    </picture>
                    <div class="prize-inner">
                        <div class="prize-content">
                            <div class="h4 prize-value"><?= Html::encode($model->prize_four) ?></div>
                            <div class="h6 prize-place"><?= Html::encode($model->getAttributeLabel('prize_four')) ?></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>


        <?php if ($specialPrizes) : ?>
        <div class="content-block">
            <h6 class="content-block__title">special prizes</h6>
            <div class="prizes--sm">
                <?php foreach ($specialPrizes as $specialKey => $specialPrize) : $pictureKey++; ?>
                <div class="prize">
                    <picture>
                        <source srcset="<?= IMG_ROOT ?>/prize<?= $pictureKey ?>.webp" type="<?= IMG_ROOT ?>/webp"/>
                        <source srcset="<?= IMG_ROOT ?>/prize<?= $pictureKey ?>.jpg"/>
                        <img class="prize-img" src="<?= IMG_ROOT ?>/prize<?= $pictureKey ?>.jpg" alt=""/>
                    </picture>
                    <div class="prize-inner">
                        <div class="prize-content">
                            <div class="h4 prize-value"><?= Html::encode($specialPrize->money) ?></div>
                            <div class="h6 prize-place"><?= Html::encode($specialPrize->description) ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($secondaryPrizes) : ?>
        <div class="content-block">
            <h6 class="content-block__title">secondary prizes</h6>
            <div class="prizes--sm">
                <?php foreach ($secondaryPrizes as $secondaryKey => $secondaryPrize) : ?>
                <div class="prize">
                    <div class="prize-inner">
                        <div class="prize-content">
                            <div class="h4 prize-value"><?= Html::encode($secondaryPrize->money) ?></div>
                            <div class="h6 prize-place"><?= Html::encode($secondaryPrize->description) ?></div>
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
                        <button class="btn btn--sm js-ajax-popup" data-url="<?= Url::to(['prize/update', 'id' => $model->id]) ?>">edit prizes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->render('_tournament_share') ?>
