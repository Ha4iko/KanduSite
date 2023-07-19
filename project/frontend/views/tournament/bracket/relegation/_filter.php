<?php

use frontend\models\Bracket;
use frontend\models\BracketRelegationDuelsForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this View */
/* @var $model BracketRelegationDuelsForm */
/* @var $bracket \common\models\Bracket\Relegation */

?>
<?php if (!isset(Yii::$app->params['preview'])) : ?>
<div class="container--sm">
    <div class="filter filter--admin js-scroll">
        <div class="filter-main">
            <div class="filter-wrap">
                <div class="filter-inner">
                    <div class="filter-btns">
                        <div class="filter-btn">
                            <button type="button" class="btn btn--sm js-ajax-popup" data-pjax="0"
                                    data-url="<?= Url::to(['/bracket-relegation/update-bracket', 'id' => $bracket->tournament_id, 'bracketId' => $bracket->id]) ?>">
                                Edit Bracket
                            </button>
                        </div>
                        <?php if (!$bracket->getCompletedDuelsCount()) : ?>
                        <div class="filter-btn">
                            <button type="button" class="btn btn--sm js-ajax-popup" data-pjax="0"
                                    data-url="<?= Url::to(['/bracket-relegation/update-participants', 'id' => $bracket->tournament_id, 'bracketId' => $bracket->id]) ?>">
                                <?= $bracket->insertedParticipantsCount ? 'Edit' : 'Insert' ?> participants
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>