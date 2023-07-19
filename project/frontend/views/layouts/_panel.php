<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use frontend\models\Tournament;
use frontend\models\search\TournamentSearch;

/* @var $this View */
/* @var $tournament Tournament */
/* @var $action string */

$needPanelPage = (Yii::$app->controller->id == 'tournament') && in_array(Yii::$app->controller->action->id, [
        'rules', 'view', 'prizes', 'media', 'schedule',
        'winners', 'participants', 'brackets',
    ]);
$tournament = $this->params['tournament'] ?? null;
$action = $this->params['action'] ?? '';

$isBracketPage = Yii::$app->controller->action->id == 'brackets';

if ($needPanelPage && is_object($tournament) && !isset(Yii::$app->params['preview']) &&
    Yii::$app->user->can('updateTournament', ['tournamentId' => $tournament->id]) ) :
?>

<div class="a-panel">
    <?php $form = ActiveForm::begin([
        'id' => 'panelForm',
        'action' => ['/tournament/edit'],
        'method' => 'post',
    ]); ?>
    <?= Html::activeHiddenInput($tournament, 'id') ?>
    <?= Html::hiddenInput('action', $action) ?>
    <div class="container">
        <div class="a-panel-container">
            <div class="a-panel-left">
                <?php if (Yii::$app->user->can('updateTournament', ['tournamentId' => $tournament->id])) : ?>
                <button type="button" class="btn btn--sm js-ajax-popup" data-url="<?= Url::to(['tournament/update', 'id' => $tournament->id]) ?>">
                    edit main info
                </button>
                <?php endif; ?>
            </div>
            <div class="a-panel-right">
                <div class="a-panel-items">

                    <div class="a-panel-item">
                        <div class="a-panel-item__prop prop">
                            status
                        </div>
                        <div class="a-panel-item__value">
                            <div class="select select--sm">
                                <div class="select-btn">
                                    <?= Html::activeDropDownList($tournament, 'status',
                                        TournamentSearch::getStatusLabels(),
                                        [
                                            'id' => 'select' . uniqid(),
                                            'data-drop' => 'select--sm',
                                            'data-style' => '2',
                                            'class' => 'js-select'
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
                        </div>
                    </div>

                    <div class="a-panel-item">
                        <div class="a-panel-btns">
                            <?php if ($isBracketPage) : ?>
                                <button type="button" class="btn btn--sm js-panel-preview">
                                    preview
                                </button>
                                <button type="button" class="btn btn--sm js-panel-save">Save</button>
                            <?php else : ?>
                                <a target="_blank" class="btn btn--sm"
                                    href="<?= Url::current(['preview' => 'yes']) ?>">
                                    preview
                                </a>
                                <button type="submit" class="btn btn--sm">Save</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php endif; ?>

<?php
if ($isBracketPage) {
$js = <<<JS
    $('.js-panel-preview').on('click', function() {
        let bracketForm = $('#bracketForm'); 
        bracketForm.attr('target', '_blank');
        bracketForm.attr('action', bracketForm.attr('action').replace('?preview=yes', ''));
        bracketForm.attr('action', bracketForm.attr('action') + '?preview=yes');
        $('#bracketForm input[name="Tournament[status]"]').val( 
            $('#panelForm select[name="Tournament[status]"]').val()
        );
        bracketForm.submit();
    });
    $('.js-panel-save').on('click', function() {
        let bracketForm = $('#bracketForm');
        bracketForm.removeAttr('target');
        bracketForm.attr('action', bracketForm.attr('action').replace('?preview=yes', ''));
        $('#bracketForm input[name="Tournament[status]"]').val( 
            $('#panelForm select[name="Tournament[status]"]').val()
        );
        bracketForm.submit();
    });
JS;
$this->registerJs($js);
}