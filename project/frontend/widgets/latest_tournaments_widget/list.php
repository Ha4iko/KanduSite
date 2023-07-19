<?php

use frontend\widgets\TournamentCardWidget;
use frontend\models\Tournament;
use yii\web\View;

/* @var $this View */
/* @var $tournaments Tournament[] */
?>

<?php foreach ($tournaments as $tournament) {
    echo TournamentCardWidget::widget(['model' => $tournament]);
} ?>
