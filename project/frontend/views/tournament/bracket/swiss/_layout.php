<?php

use yii\web\View;
use yii\helpers\Html;
use common\models\Bracket\Swiss;
use common\models\Bracket\Swiss\Round;
use common\models\Bracket\Swiss\Duel;
use common\models\Tournament;
use yii\widgets\Pjax;
use common\models\PlayerClass;

/* @var $this View */
/* @var $tableRows array */
/* @var $bracket Swiss */
/* @var $round Round */
/* @var $duels Duel[] */
/* @var $participants array */
/* @var $classIds array */

$disableForm = $disableForm ?? false;

$errors = [];
$duelsRender = [];

foreach ($duels as $duelModel) {
    if ($duelModel->round_id != $round->id) continue;

    if ($duelError = $duelModel->model->getFirstErrors()) {
        $errors = array_merge($errors, $duelError);
    }
    $duelsRender[] = $duelModel;
}

if (is_object($bracket)) :
?>

<?php if (!$disableForm): ?>
    <?= $this->render('_filter_generator', [
        'model' => $model,
        'bracket' => $bracket,
        'round' => $round
    ]) ?>
<?php endif; ?>


<?php if (!$disableForm && $errors): ?>
    <div class="container--sm" style="padding-top: 2em; color: #DF0D14;">
        <?= implode('<br>', $errors) ?>
    </div>
<?php endif; ?>


<div class="filter-content filter-content--sm">
    <div class="groups">
        <div class="group-wrap">
            <div class="group group--admin">
                <div class="group-items">
                <?php foreach ($duelsRender as $i => $duel) :
                        $index = $this->params['__index']++;
                        if($disableForm === false){
                        if(!$disableForm):
                            $canSetPlayer = true;
                            $canSetScore = true;
                        endif;
                            
    
                            /*
                        $canSetPlayer = $round->order === 1 && $duel->active && $bracket->editable_participants;
                        $canSetScore = $duel->player_1 && $duel->player_2 && $bracket->editable_scores &&
                            ($bracket->getLastRoundOrderWithParticipants() == $round->order); */
                            if ($bracket->tournament->status == Tournament::STATUS_COMPLETED) {
                                $canSetPlayer = false;
                                $canSetScore = false;
                            }
                            ?>
                          <?=  $this->render('_duel', [
                                'duel' => $duel,
                                'index' => $index,
                                'bracket' => $bracket,
                                'canSetPlayer' => $canSetPlayer,
                                'canSetScore' => $canSetScore,
                                'participants' => $participants,
                                'disableForm' => $disableForm || isset(Yii::$app->params['preview']),
                                'round' => $round,
                                'classIds' => $classIds,
                            ]); }?>
                       <?php if($disableForm == true) {?>

<?php $player = 1;
$playerIdField = 'player_' . $player;
$playerField = 'player' . $player;
$nameField = $duel->teamMode ? 'name' : 'nick';
$classId = $classIds[$duel->$playerIdField] ?? 0;
$scoreField = 'score_' . ($player === 1 ? 'one' : 'two');?>


<div class="group-item js-group-item">
    <?= Html::hiddenInput('Duels[' . $index . '][id]', $duel->id) ?>
    <div class="group-item__inner">
        <div class="group-item__num">
            <?= $duel->order ?>
        </div>
        <div class="group-item__results">

        <?php $cc = PlayerClass::findOne($classId);?>
        <div class="group-item__result <?= $duel->$playerIdField && $duel->$playerIdField === $duel->winner_id ? 'active' : '' ?> js-group-result">
        <div class="brackets-item__title"
             style="color: <?=  $cc->avatar;  ?> !important;"><?= Html::encode($duel->$playerField->$nameField) ?></div>


    <div class="group-item__score"><?= $duel->$scoreField ?: '-' ?></div>
</div>
<?php $player = 2;
$playerIdField = 'player_' . $player;
$playerField = 'player' . $player;
$nameField = $duel->teamMode ? 'name' : 'nick';
$classId = $classIds[$duel->$playerIdField] ?? 0;
$scoreField = 'score_' . ($player === 1 ? 'one' : 'two');?>

<?php $cc = PlayerClass::findOne($classId);?>
        <div class="group-item__result <?= $duel->$playerIdField && $duel->$playerIdField === $duel->winner_id ? 'active' : '' ?> js-group-result">

        <div class="brackets-item__title"
             style="color: <?=  $cc->avatar;  ?> !important;"><?= Html::encode($duel->$playerField->$nameField) ?></div>
             <div class="group-item__score"><?= $duel->$scoreField ?: '-' ?></div>
</div>


        </div>
    </div>
</div>
<?php }?>                      
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>