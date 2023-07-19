<?php

use frontend\models\BracketSwissForm;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var $this View */
/** @var $model BracketSwissForm */

?>
<div class="popup" id="adminAddBracketSwiss">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'bracketSwissForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(['/bracket-swiss/update-bracket', 'id' => $model->tournament_id, 'bracketId' => $model->id], 'post', [
                'data-pjax' => 1,
                'enctype' => 'multipart/form-data',
            ]); ?>
            <?= Html::activeHiddenInput($model, 'id') ?>

            <div class="popup-head">
                <div class="popup-close js-popup-close">
                    <a class="link-back" href="#">
                        <svg class="icon">
                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                        </svg>close
                    </a>
                </div>
                <div class="popup-title h3"><?= $model->isNewRecord ? 'Add' : 'Edit' ?> bracket <span>/ swiss</span></div>
            </div>

            <div class="popup-content">

                <div class="content-block">
                    <div class="control">
                        <div class="control-side">
                            <div class="prop">Name of Bracket <span>*</span></div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field <?= $model->hasErrors('title') ? 'error' : '' ?>">
                                    <?= Html::activeTextInput($model, 'title', [
                                        'placeholder' => 'enter name of table',
                                        'class' => 'field'
                                    ]) ?>
                                    <?= Html::error($model, 'title', ['class' => 'field-error']) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($model->editable) : ?>
                    <div class="control">
                        <div class="control-side">
                            <div class="prop">Number of players in the table <span>*</span></div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field control-field--sm <?= $model->hasErrors('participants') ? 'error' : '' ?>">
                                    <?= Html::activeTextInput($model, 'participants', [
                                        'type' => 'number',
                                        'placeholder' => 'enter number',
                                        'class' => 'field'
                                    ]) ?>
                                    <?= Html::error($model, 'participants', ['class' => 'field-error']) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="control">
                        <div class="control-side">
                            <div class="prop">Best of</div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field control-field--sm <?= $model->hasErrors('best_of') ? 'error' : '' ?>">
                                    <div class="select">
                                        <div class="select-btn">
                                            <?= Html::activeDropDownList($model, 'best_of',
                                                [
                                                    1 => 1,
                                                    2 => 2,
                                                    3 => 3,
                                                    4 => 4,
                                                    5 => 5,
                                                ],
                                                [
                                                    'data-placeholder' => 'choose',
                                                    'data-style' => '2',
                                                    'class' => 'js-select',
                                                ]
                                            ) ?>
                                        </div>
                                        <div class="select-drop">
                                            <div class="close js-close">
                                                <div class="close-inner">
                                                    <div class="close-icon">
                                                        <svg class="icon">
                                                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                        </svg>
                                                    </div>
                                                    <div class="close-text">close</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?= Html::error($model, 'best_of', ['class' => 'field-error']) ?>
                                </div>
                                <div class="control-field__hint">
                                    <div class="text--sm">If it is even, then there will be a draw, if it is odd, then there will be no draw.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="control">
                        <div class="control-side">
                            <div class="prop">Number of rounds</div>
                        </div>
                        <div class="control-content">
                            <div class="control-fields">
                                <div class="control-field control-field--sm <?= $model->hasErrors('round_count') ? 'error' : '' ?>">
                                    <div class="select">
                                        <div class="select-btn">
                                            <?= Html::activeDropDownList($model, 'round_count',
                                                [
                                                    // 1 => 1,
                                                    // 2 => 2,
                                                    // 3 => 3,
                                                    // 4 => 4,
                                                    // 5 => 5,
                                                    // 6 => 6,
                                                    // 7 => 7,
                                                    // 8 => 8,
                                                    // 9 => 9,
                                                    // 10 => 10,
                                                ],
                                                [
                                                    'data-placeholder' => 'choose',
                                                    'data-style' => '2',
                                                    'class' => 'js-select',
                                                ]
                                            ) ?>
                                        </div>
                                        <div class="select-drop">
                                            <div class="close js-close">
                                                <div class="close-inner">
                                                    <div class="close-icon">
                                                        <svg class="icon">
                                                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                        </svg>
                                                    </div>
                                                    <div class="close-text">close</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?= Html::error($model, 'round_count', ['class' => 'field-error']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

            </div>

            <div class="a-footer a-footer--start">
                <button class="btn" type="submit">save and continue</button>
                <a class="btn js-popup-close" data-pjax="0" href="#">cancel</a>
            </div>

            <?php if (Yii::$app->request->isPost) : ?>
                <script>
                    syncRounds(<?= $model->round_count ?: '0' ?>);
                </script>
            <?php endif; ?>

            <?= Html::endForm(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php

$js = <<<JS
    function syncRounds(rounds = 0) {
        let countParticipants = parseInt($('[name="BracketSwissForm[participants]"]').val()),
            selectRounds = $('[name="BracketSwissForm[round_count]"]'),
            maxRounds = 10;
        if (isNaN(countParticipants)) countParticipants = 0;
        
        if (countParticipants <= 128) maxRounds = 9;
        if (countParticipants <= 64) maxRounds = 8;
        if (countParticipants <= 32) maxRounds = 7;
        if (countParticipants <= 16) maxRounds = 6;
        if (countParticipants <= 8) maxRounds = 5;
        
        selectRounds.find('option').remove();
        selectRounds.val(null);
        for (var i = 1; i <= maxRounds; i++) {
            $("<option />", {value: i, text: i}).appendTo(selectRounds);
        }
        if (rounds) {
            selectRounds.val(rounds);
        }
        
        selectReInit();
    }
    
    $(document).on('input', '[name="BracketSwissForm[participants]"]', function (){
        syncRounds();
    });    

    syncRounds();
JS;
if (Yii::$app->request->isGet) {
    $this->registerJs($js);
}

