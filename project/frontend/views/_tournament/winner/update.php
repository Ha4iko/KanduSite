<?php

use frontend\models\WinnersForm;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;
use frontend\assets\SelectWithClearAsset;

/** @var $this View */
/** @var $model WinnersForm */
/** @var $nicks array */

$pictureKey = 4;

$winnerCount = 0;
foreach ($model->prizes as $dynaPrize) {
    if ($dynaPrize) $winnerCount++;
}
if ($model->player_one) $winnerCount++;
if ($model->player_two) $winnerCount++;
if ($model->player_three) $winnerCount++;
if ($model->player_four) $winnerCount++;

SelectWithClearAsset::register($this);

$jsParticipants = [];
foreach ($nicks as $k => $name) {
    if ($name)
        $jsParticipants[] = [
            'id' => $k,
            'name' => $name
        ];
}

$this->registerJsVar('__nicks_all', $jsParticipants);
?>
<div class="popup" id="adminAddWinners">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'winnersForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(['/winner/update', 'id' => $model->tournament_id], 'post', [
                'data-pjax' => 1,
                'enctype' => 'multipart/form-data',
            ]); ?>
            <div class="popup-head">
                <div class="popup-close js-popup-close">
                    <a class="link-back" href="#">
                        <svg class="icon">
                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                        </svg>close</a>
                </div>
                <div class="popup-title h3">
                    <?= $winnerCount ? 'Edit' : 'Insert' ?> Winners
                </div>
            </div>
            <div class="popup-content">
                <div class="content">

                    <div class="content-block">
                        <h6 class="content-block__title">Standard Prizes</h6>

                        <?php for ($i = 1; $i < 5; $i++) {
                            echo $this->render('_update_standard-prize', [
                                'i' => $i,
                                'model' => $model,
                                'owners' => $nicks,
                                'isTeam' => false,
                            ]);
                        } ?>
                    </div>

                    <div class="content-block">
                        <h6 class="content-block__title">special prizes</h6>

                        <?php foreach ($model->prizesSpecial as $i => $prizeSpecial) {
                            $pictureKey++;
                            echo $this->render('_update_special-prize', [
                                'i' => $i,
                                'pictureKey' => $pictureKey,
                                'model' => $model,
                                'owners' => $nicks,
                                'prize' => $prizeSpecial,
                                'isTeam' => false,
                            ]);
                        } ?>
                    </div>


                    <div class="content-block">
                        <h6 class="content-block__title">secondary prizes</h6>

                        <?php foreach ($model->prizesSecondary as $i => $prizeSpecial) {
                            echo $this->render('_update_secondary-prize', [
                                'i' => $i,
                                'model' => $model,
                                'owners' => $nicks,
                                'prize' => $prizeSpecial,
                                'isTeam' => false,
                            ]);
                        } ?>
                    </div>
                </div>
            </div>
            <div class="a-footer a-footer--start">
                <button class="btn" type="submit">save and continue</button>
                <a class="btn js-popup-close" href="#">cancel</a>
            </div>
            <?= Html::endForm(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$js = <<<JS
    updateAvailableParticipants(
        '#winnersform-player_one, #winnersform-player_two, #winnersform-player_three, #winnersform-player_four', 
    window.__nicks_all, false);

    updateAvailableParticipants(
        '.js-spec-mark', 
    window.__nicks_all, false);

    updateAvailableParticipants(
        '.js-second-mark', 
    window.__nicks_all, false);

    selectCustomReInit(); 
JS;

$this->registerJs($js);