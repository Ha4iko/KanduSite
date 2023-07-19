<?php

use yii\helpers\Html;
use frontend\models\BracketTableRowTeamForm;
use common\models\Tournament;

/* @var $model array */
/* @var $rowsForm BracketTableRowTeamForm */

$readOnly = $rowsForm->bracket->tournament->status == Tournament::STATUS_COMPLETED;
if ($model['id']) :
?>
<tr>
    <td>
    <?php foreach ($model['columns'] ?? [] as $column) :
        $cellFakeAddress = (intval($model['id']) * 10000000) + intval($column['id']);
        if($column['title'] == 'top'):
    ?>
        
            <?php if (!isset(Yii::$app->params['preview']) && !$readOnly) : ?>
                <?= Html::hiddenInput('BracketTableCellTeam[' . $cellFakeAddress . '][id]', $column['cell_id'], []) ?>
                <?= Html::hiddenInput('BracketTableCellTeam[' . $cellFakeAddress . '][bracket_table_row_id]', $model['id'], []) ?>
                <?= Html::hiddenInput('BracketTableCellTeam[' . $cellFakeAddress . '][bracket_table_column_id]', $column['id'], []) ?>
                <div class="cell-edit js-cell-edit">
                    <div class="cell-edit__text"><?= $column['top'] ? Html::encode($column['top'])  : '-' ?></div>
                    <?= Html::textInput('BracketTableCellTeam[' . $cellFakeAddress . '][top]', $column['top'], [
                        'class' => 'cell-edit__input field field--sm',
                    ]) ?>
                </div>
            <?php else : ?>
                <?= $column['top'] ? Html::encode($column['top'])  : '-' ?>
            <?php endif; endif; ?>
        
    <?php endforeach; ?>
    </td>
    <td class="table-col--left js-find-me">
        <?= Html::encode($model['team_name']) ?>
    </td>

    <?php foreach ($model['columns'] ?? [] as $column) :
        $cellFakeAddress = (intval($model['id']) * 10000000) + intval($column['id']);
        if($column['title']!='top'):
    ?>
        <td>
            <?php if (!isset(Yii::$app->params['preview']) && !$readOnly) : ?>
                <?= Html::hiddenInput('BracketTableCellTeam[' . $cellFakeAddress . '][id]', $column['cell_id'], []) ?>
                <?= Html::hiddenInput('BracketTableCellTeam[' . $cellFakeAddress . '][bracket_table_row_id]', $model['id'], []) ?>
                <?= Html::hiddenInput('BracketTableCellTeam[' . $cellFakeAddress . '][bracket_table_column_id]', $column['id'], []) ?>
                <div class="cell-edit js-cell-edit">
                    <div class="cell-edit__text"><?= $column['value'] ? Html::encode($column['value'])  : '-' ?></div>
                    <?= Html::textInput('BracketTableCellTeam[' . $cellFakeAddress . '][value]', $column['value'], [
                        'class' => 'cell-edit__input field field--sm',
                    ]) ?>
                </div>
            <?php else : ?>
                <?= $column['value'] ? Html::encode($column['value'])  : '-' ?>
            <?php endif; endif; ?>
        </td>
    <?php endforeach; ?>
</tr>

<?php endif; ?>