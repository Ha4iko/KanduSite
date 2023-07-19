<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Settings';
$dataProvider->pagination->setPageSize(100);

?>
<div class="setting-index">

    <h1 class="mt-3 mb-4"><?= Html::encode($this->title) ?></h1>

  <p>
      <?= Html::a('<span class="glyphicon glyphicon-plus"></span> New setting',
          ['create'],
          ['class' => 'btn btn-success']) ?>
  </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'key',
            'value',

            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '50'],
                'template' => '{update} {delete}',
            ],
        ],
        // 'pager' => [
        //     'class' => 'backend\models\CustomLinkPager'
        // ],
        'layout' => "{items}\n{pager}"
    ]); ?>

    <?php Pjax::end(); ?>

</div>
