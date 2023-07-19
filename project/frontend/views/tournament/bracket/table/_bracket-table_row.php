<?php

use yii\helpers\Html;
use frontend\models\search\BracketTableRowSearch;
use yii\helpers\Url;

/* @var $model array */
/* @var $searchModel BracketTableRowSearch */

if ($model['id']) :
$cc = $model['collor_class'];
?>
<tr>
    <td>
       <?php foreach ($model['columns'] as $column) : ?>

        <?php if($column['title'] == 'top') : ?>
        <?= $column['top'] ? Html::encode($column['top'])  : '-' ?>
        <?php endif; ?>
        <?php endforeach; ?>
    </td>
    <td class="table-col--left">
        <div class="table-player">
            <div class="table-player__avatar">
                <img src="<?= $model['player_avatar'] ?>" alt=""/>
            </div>
            <?php if ($model['player_link']) : ?>
                <a target="_blank" href="<?= $model['player_link'] ?>" class="table-player__name"
                   style="color: <?= Html::encode($model['collor_class']) ?> !important;">
                    <?= Html::encode($model['nick']) ?>
                </a>
            <?php else : ?>
                <div class="table-player__name" style="color:<?= Html::encode($model['collor_class']) ?> !important;">
                    <?= Html::encode($model['nick']) ?>
                </div>
            <?php endif; ?>
        </div>
    </td>
    <td>
        <?= $model['class'] ? Html::encode($model['class'])  : '-' ?>
    </td>
    <td>
        <?= $model['faction_avatar'] ? "<img class=\"table-logo\" src=\"{$model['faction_avatar']}\" alt=\"\"/>" : '-' ?>
    </td>
    <td>
        <?= $model['world'] ? Html::encode($model['world'])  : '-' ?>
    </td>

    <?php foreach ($model['columns'] as $column) : ?>
        <?php if($column['title'] != 'top') : ?>
        <td>          
            <?= $column['value'] ? Html::encode($column['value'])  : '-' ?>          
        </td>
        <?php endif; ?>
    <?php endforeach; ?>
</tr>

<?php endif; ?>