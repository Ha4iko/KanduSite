<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Media */

$this->title = 'Update Media: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'All Media', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="media-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
