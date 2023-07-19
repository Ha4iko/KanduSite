<?php

use yii\helpers\Html;
use frontend\models\BracketTableRowForm;
use common\models\Tournament;

/* @var $model array */
/* @var $rowsForm BracketTableRowForm */

$readOnly = $rowsForm->bracket->tournament->status == Tournament::STATUS_COMPLETED;
if ($model['id']) :
?>
<tr>
    <td>

      <?php foreach ($model['columns'] ?? [] as $column) :
        $cellFakeAddress = (intval($model['id']) * 10000000) + intval($column['id']);
         
        if($column['title'] == 'top') :
        ?>
        
            <?php if (!isset(Yii::$app->params['preview']) && !$readOnly ) : ?>
                <?= Html::hiddenInput('BracketTableCell[' . $cellFakeAddress . '][id]', $column['cell_id'], []) ?>
                <?= Html::hiddenInput('BracketTableCell[' . $cellFakeAddress . '][bracket_table_row_id]', $model['id'], []) ?>
                <?= Html::hiddenInput('BracketTableCell[' . $cellFakeAddress . '][bracket_table_column_id]', $column['id'], []) ?>
                <div class="cell-edit js-cell-edit">
                    <div class="cell-edit__text"><?= !empty($column['top']) ? Html::encode($column['top'])  : '-' ?></div>
                    <?= Html::textInput('BracketTableCell[' . $cellFakeAddress . '][top]', $column['top'], [
                        'class' => 'cell-edit__input field field--sm',
                        'autocomplete' => 'off'
                    ]) ?>
                </div>
            <?php else : ?>
                <?= $column['top'] ? Html::encode($column['top'])  : '-' ?>
            <?php endif; 
            endif;?>
        
      <?php endforeach; ?>
        
    </td>
    <td class="table-col--left">
        <div class="table-player">
            <div class="table-player__avatar">
                <img src="<?= $model['player_avatar'] ?>" alt=""/>
            </div>
            <?php if ($model['player_link']) : ?>
                <a target="_blank" href="<?= $model['player_link'] ?>" class="table-player__name no-decor"
                   style="color: <?= Html::encode($model['collor_class']) ?> !important;">
                    <?= Html::encode($model['nick']) ?>
                </a>
            <?php else : ?>
                <div class="table-player__name" style="color: <?= Html::encode($model['collor_class']) ?> !important;">
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

    <?php foreach ($model['columns'] ?? [] as $column) :
        $cellFakeAddress = (intval($model['id']) * 10000000) + intval($column['id']);
        if($column['title'] != 'top') : ?>                  
        <td>    
            <?php if (!isset(Yii::$app->params['preview']) && !$readOnly ) : ?>
                <?= Html::hiddenInput('BracketTableCell[' . $cellFakeAddress . '][id]', $column['cell_id'], []) ?>
                <?= Html::hiddenInput('BracketTableCell[' . $cellFakeAddress . '][bracket_table_row_id]', $model['id'], []) ?>
                <?= Html::hiddenInput('BracketTableCell[' . $cellFakeAddress . '][bracket_table_column_id]', $column['id'], []) ?>
                <div class="cell-edit js-cell-edit">
                    <div class="cell-edit__text"><?= !empty($column['value']) ? Html::encode($column['value'])  : '-' ?></div>
                    <?= Html::textInput('BracketTableCell[' . $cellFakeAddress . '][value]', $column['value'], [
                        'class' => 'cell-edit__input field field--sm',
                        'autocomplete' => 'off'
                    ]) ?>
                </div>
            <?php else : ?>
                <?= $column['value'] ? Html::encode($column['value'])  : '-' ?>
            <?php endif;?>
        </td>
        <?php endif;?>
    <?php endforeach; ?>
</tr>

<?php endif; ?>