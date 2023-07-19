<?php

use frontend\widgets\MediaCardWidget;
use frontend\models\Media;
use yii\web\View;

/* @var $this View */
/* @var $media Media[] */
?>

<?php foreach ($media as $mediaModel) {
    echo MediaCardWidget::widget(['model' => $mediaModel]);
} ?>
