<?php

use frontend\models\Bracket;
use frontend\models\Tournament;
use yii\web\View;

/* @var $this View */
/* @var $tournament Tournament */
/* @var $bracket Bracket */

$this->title = 'Bracket relegation of tournament';

?>

<?= $this->render('_layout', [
    'model' => $model,
    'bracket' => $bracket
]) ?>