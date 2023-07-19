<?php

use yii\helpers\Html;
use frontend\models\search\BracketTableRowTeamSearch;
use yii\helpers\Url;

/* @var $model array */
/* @var $searchModel BracketTableRowTeamSearch */

if ($model['id']) :
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
        <?= Html::encode($model['team_name']) ?>
    </td>

    <?php foreach ($model['columns'] as $column) :
        if($column['title']!='top'): ?>
        <td>
            <?= $column['value'] ? Html::encode($column['value'])  : '-' ?>
        </td>
    <?php endif; endforeach; ?>
</tr>
<?php endif; ?>