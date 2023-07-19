<?php

use yii\web\View;
use frontend\models\BracketTableRowForm;
use yii\helpers\Html;
use frontend\models\Bracket;

/* @var $this View */
/* @var $tableRows array */
/* @var $model BracketTableRowForm */
/* @var $bracket \common\models\Bracket\Relegation */
/* @var $participants array */

$this->params['__index'] = 0;
$readOnly = !isset($model);

?>

<?php if (isset($model) && $model->hasErrors()): ?>
    <div class="container--sm" style="padding-top: 2em; color: #DF0D14;">
        <?= Html::encode(array_values($model->getFirstErrors())[0]) ?>
    </div>
<?php endif; ?>

<div class="filter-content">
    <div class="tabs fullscreen-content" id="fullscreen-content">
        <div class="tabs-item" id="bracket1">
            <?= $this->render('_bracket', [
                'bracket' => $bracket,
                'rounds' => $bracket->roundsMain,
                'participants' => $participants,
                'readOnly' => $readOnly
            ]) ?>
        </div>
        <?php if ($bracket->second_defeat) : ?>
            <div class="tabs-item" id="bracket2">
                <?= $this->render('_bracket', [
                    'bracket' => $bracket,
                    'rounds' => $bracket->roundsDefeat,
                    'isDefeat' => true,
                    'participants' => $participants,
                    'readOnly' => $readOnly
                ]) ?>
            </div>
        <?php endif; ?>
        <div class="tabs-item" id="bracket3">
            <?= $this->render('_bracket', [
                'bracket' => $bracket,
                'rounds' => $bracket->roundsGrand,
                'participants' => $participants,
                'isGrand' => true,
                'readOnly' => $readOnly
            ]) ?>
        </div>
    </div>
</div>
