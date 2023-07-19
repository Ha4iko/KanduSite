<?php

use common\models\Bracket\Swiss;
use common\models\Bracket\Swiss\Duel;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;
use frontend\assets\DataTablesAsset;

/* @var $this View */
/* @var $duels Duel[] */
/* @var $bracket Swiss */

$this->title = 'Bracket swiss of tournament';

$this->params['__index'] = 0;
$classIds = $bracket->tournament->getPlayerClassIds();

DataTablesAsset::register($this);
?>

<?php if (is_object($bracket)) : ?>
    <div class="tabs">
        <?php
        $rounds = $bracket->rounds;

        foreach ($rounds as $round) : ?>
            <div class="tabs-item" id="round<?= $round->order ?>">
                <?= $this->render('_layout', [
                    'duels' => $duels,
                    'round' => $round,
                    'bracket' => $bracket,
                    'classIds' => $classIds,
                    'disableForm' => true,
                ]) ?>
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
JS;

$this->registerJs($js);