<?php

use common\models\Bracket\Group;
use frontend\models\BracketGroupDuelsForm;
use yii\web\View;
use yii\helpers\Html;

/* @var $this View */
/* @var $model BracketGroupDuelsForm */
/* @var $bracket Group */
/* @var $round \common\models\Bracket\Swiss\Round */

$canGenerate = $round->prevRound->completed;

?>
<?php if (!isset(Yii::$app->params['preview'])) : ?>
<?php if ($round->prevRound && !$round->filled): ?>
<div class="filter filter--admin js--scroll">
    <div class="filter-main">
        <div class="filter-wrap">
            <div class="filter-inner">
                <div class="filter-btns">
                    <div class="filter-btn">
                        <?= Html::a('generate round',
                            ['/bracket-swiss/generate-round'], [
                                'data-method' => 'POST',
                                'data-params' => [
                                    'bracketId' => $bracket->id,
                                    'roundId' => $round->id
                                ],
                                'data-pjax' => '0',
                                'class' => 'btn btn--sm' . ($canGenerate ? '' : ' disabled')
                            ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>