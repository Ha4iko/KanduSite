<?php

use common\models\Bracket\Relegation;
use frontend\models\BracketRelegationDuelsForm;
use frontend\models\Tournament;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;
use frontend\assets\SelectWithClearAsset;

/* @var $this View */
/* @var $model BracketRelegationDuelsForm */
/* @var $tournament Tournament */
/* @var $bracket Relegation */

$this->title = 'Bracket relegation of tournament';

$participants = $model->getParticipantsNames();

SelectWithClearAsset::register($this);

$insertedParticipantIds = $bracket->getInsertedCompletedParticipantIds(true);

$jsParticipants = [];
foreach ($participants as $k => $name) {
    if ($name && !isset($insertedParticipantIds[$k]))
        $jsParticipants[] = [
            'id' => $k,
            'name' => $name
        ];
}
$this->registerJsVar('__nicks_all', $jsParticipants);
//$this->registerJsVar('__nicks_available', $jsParticipants);

?>

<?php if (is_object($bracket)) : ?>
<!--    --><?php //Pjax::begin([
//        'id' => 'bracket-relegation-form',
//        'timeout' => 5000,
//        'enablePushState' => false,
//        'enableReplaceState' => false,
//    ]) ?>
    <?= Html::beginForm('', 'post', [
        'id' => 'bracketForm',
        'data-pjax' => 1,
    ]); ?>
    <input type="hidden" name="Tournament[id]" value="<?= $tournament->id ?>">
    <input type="hidden" name="Tournament[status]" value="<?= $tournament->status ?>">
    <?= Html::hiddenInput('__hash') ?>

    <?= $this->render('_filter', [
        'model' => $model,
        'bracket' => $bracket,
    ]) ?>

    <?php if ($bracket->bracket_type === $bracket::TYPE_RELEGATION): ?>
        <?= $this->render('_layout', [
            'model' => $model,
            'bracket' => $bracket,
            'participants' => $participants,
        ]) ?>
    <?php endif; ?>

    <?= $this->render('_filter', [
        'model' => $model,
        'bracket' => $bracket,
    ]) ?>

    <?= Html::endForm(); ?>
<!--    --><?php //Pjax::end() ?>
<?php else : ?>
    <?= $this->render('../../_list_empty', ['renderInPlace' => true]) ?>
<?php endif; ?>


<?php
$js = <<<JS
    updateAvailableParticipants('#bracketForm select[name^=BracketRelegationDuelsForm]', window.__nicks_all);
JS;

$this->registerJs($js);