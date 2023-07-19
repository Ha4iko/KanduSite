<?php

use common\models\Bracket\Swiss;
use frontend\models\BracketSwissDuelsForm;
use frontend\models\Tournament;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;
use frontend\assets\DataTablesAsset;
use frontend\assets\SelectWithClearAsset;

/* @var $this View */
/* @var $model BracketSwissDuelsForm */
/* @var $canModify bool */
/* @var $tournament Tournament */
/* @var $bracket Swiss */

$this->title = 'Bracket swiss of tournament';

$this->params['__index'] = 0;

$participants = $model->getParticipantsNames();
$classIds = $tournament->getPlayerClassIds();

DataTablesAsset::register($this);
SelectWithClearAsset::register($this);

$jsParticipants = [];
foreach ($participants as $k => $name) {
    if ($name) {
        $jsParticipants[] = [
            'id' => $k,
            'name' => $name
        ];
    }
}

$this->registerJsVar('__nicks_all', $jsParticipants);

?>

<?php if (is_object($bracket)) : ?>
<!--    --><?php //Pjax::begin([
    //     'id' => 'bracket-swiss-form',
    //     'timeout' => 5000,
    //     'enablePushState' => false,
    //     'enableReplaceState' => false,
    // ]) ?>
    <?= Html::beginForm('', 'post', [
        'id' => 'bracketForm',
        'data-pjax' => 1,
    ]); ?>
    <input type="hidden" name="Tournament[id]" value="<?= $tournament->id ?>">
    <input type="hidden" name="Tournament[status]" value="<?= $tournament->status ?>">
    <?= Html::hiddenInput('__hash') ?>

    <div class="tabs">
        <?php
        $rounds = $bracket->rounds;

        foreach ($rounds as $round) : ?>
            <div class="tabs-item" id="round<?= $round->order ?>">
                <?= ($round->order == 1)
                    ? $this->render('_filter', [
                        'model' => $model,
                        'bracket' => $bracket,
                    ]) : '' ?>

                <?= $this->render('_layout', [
                    'duels' => $model->duels,
                    'round' => $round,
                    'bracket' => $bracket,
                    'participants' => $participants,
                    'model' => $model,
                    'classIds' => $classIds,
                ]) ?>

                <?= ($round->order == 1)
                    ? $this->render('_filter', [
                        'model' => $model,
                        'bracket' => $bracket,
                    ]) : '' ?>
            </div>
        <?php endforeach; ?>

        <?php if ($rounds) : ?>
            <div class="tabs-item" id="standings">
                <?= $this->render('_standings', [
                    'model' => $model,
                    'bracket' => $bracket,
                    'classIds' => $classIds,
                ]) ?>
            </div>
        <?php endif; ?>
    </div>

    <?= Html::endForm(); ?>
<!--    --><?php //Pjax::end() ?>



<?php else : ?>
    <?= $this->render('_list_empty', ['renderInPlace' => true]) ?>
<?php endif; ?>

<?php
$js = <<<JS
    $('.table-content table').DataTable({
        paging: false,
        searching: false,
        select: false,
        info: false
    });
    
    updateAvailableParticipants('#bracketForm select[name^=Duels]', window.__nicks_all);
JS;

$this->registerJs($js);