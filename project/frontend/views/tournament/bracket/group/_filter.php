<?php

use common\models\Bracket\Group;
use frontend\models\BracketGroupDuelsForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this View */
/* @var $model BracketGroupDuelsForm */
/* @var $bracket Group */

?>
<?php if (!isset(Yii::$app->params['preview'])) : ?>
<div class="filter filter--admin js-scroll">
    <div class="filter-main">
        <div class="filter-wrap">
            <div class="filter-inner">
                <div class="filter-btns">
                    <div class="filter-btn">
                        <button type="button" class="btn btn--sm js-ajax-popup" data-pjax="0"
                                data-url="<?= Url::to(['/bracket-group/update-bracket', 'id' => $bracket->tournament_id, 'bracketId' => $bracket->id]) ?>">
                            Edit Bracket
                        </button>
                    </div>
                    <?php if ($bracket->editable_participants) : ?>
                    <div class="filter-btn">
                        <button type="button" class="btn btn--sm js-ajax-popup" data-pjax="0"
                                data-url="<?= Url::to(['/bracket-group/update-participants', 'id' => $bracket->tournament_id, 'bracketId' => $bracket->id]) ?>">
                            <?= $model->attachedParticipantsIds ? 'Edit' : 'Insert' ?> participants
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>