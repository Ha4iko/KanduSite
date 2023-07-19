<?php

use yii\web\View;
use yii\helpers\Html;
use common\models\Bracket\Group;
use common\models\Bracket\Group\Round;
use common\models\Bracket\Group\Duel;
use common\models\Tournament;

/* @var $this View */
/* @var $tableRows array */
/* @var $bracket Group */
/* @var $round Round */
/* @var $duels Duel[] */
/* @var $participants array */

$disableForm = $disableForm ?? false;
$completedDuelsCount = $bracket->completedDuelsCount;

$errors = [];
$groups = [];

foreach ($duels as $duelModel) {
    if ($duelModel->round_id != $round->id) continue;

    if ($duelError = $duelModel->model->getFirstErrors()) {
        $errors = array_merge($errors, $duelError);
    }
    $group = $duelModel->group;
    $groups[$group->id]['model'] = $group;
    $groups[$group->id]['duels'][] = $duelModel;
}

if (is_object($bracket)) :

?>

<?php if (!$disableForm && $errors): ?>
<div class="container--sm" style="padding-top: 2em; color: #DF0D14;">
    <?= implode('<br>', $errors) ?>
</div>
<?php endif; ?>

<div class="filter-content filter-content--sm">
    <div class="groups">
        <?php
        $idx = 0;
        $groupsCount = count($groups);
        foreach ($groups as $groupId => $group) :
            $idx++;
        ?>

            <?php if ($idx % 2) : ?>
            <div class="group-wrap">
            <?php endif; ?>

                <div class="group group--admin">
                    <div class="group-title prop">
                        <?= Html::encode($group['model']->title) ?>
                    </div>
                    <div class="group-items">

                        <?php foreach ($group['duels'] as $duel) :
                            $index = $this->params['__index']++;
                            $canSetPlayer = $duel->active && !$completedDuelsCount && $bracket->editable_participants;
                            $canSetScore = $duel->player_1 && $duel->player_2 && $bracket->editable_scores;
                            if ($bracket->tournament->status == Tournament::STATUS_COMPLETED) {
                                $canSetPlayer = false;
                                $canSetScore = false;
                            }
                        ?>
                            <?= $this->render('_duel', [
                                'duel' => $duel,
                                'index' => $index,
                                'bracket' => $bracket,
                                'canSetPlayer' => $canSetPlayer,
                                'canSetScore' => $canSetScore,
                                'participants' => $participants,
                                'disableForm' => $disableForm || isset(Yii::$app->params['preview']),
                                'round' => $round,
                            ]) ?>

                        <?php endforeach; ?>

                    </div>
                </div>

            <?php if (!($idx % 2) || $groupsCount == $idx) : ?>
            </div>
            <?php endif; ?>

        <?php endforeach; ?>
    </div>
</div>

<?php endif; ?>