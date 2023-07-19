<?php

use yii\web\View;
use frontend\models\BracketTableRowTeamForm;
use yii\helpers\Html;

/* @var $this View */
/* @var $tableRows array */
/* @var $model BracketTableRowTeamForm */
/* @var $existRows int */

$tableHeaders = [];
foreach ($tableRows as $row) {
    foreach ($row['columns'] ?? [] as $col) {
        $tableHeaders[$col['id']] = $col['title'];
    }
}

?>

<div class="table table--center">
    <div class="table-content">
        <div class="table-inner">
            <table>
                <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <th class="table-col--left">
                            <div class="table-sort">
                                <div class="table-sort__text">Team</div>
                                <div class="table-sort__btn"></div>
                            </div>
                        </th>

                        <?php foreach ($tableHeaders as $tableHeader) : 
                            if($tableHeader != 'top'):?>
                        <th>
                            <div class="table-sort">
                                <div class="table-sort__text"><?= Html::encode($tableHeader) ?></div>
                                <div class="table-sort__btn"></div>
                            </div>
                        </th>
                        <?php endif; endforeach; ?>
                    </tr>
                </thead>
                <tbody>


                    <?php if ($existRows) : ?>
                        <?php foreach ($tableRows as $tableRow) : ?>
                            <?= $this->render('_bracket-table-team-form_row', [
                                'model' => $tableRow,
                                'rowsForm' => $model,
                            ]) ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <?= $this->render('_empty_rows_teams', [
                            'tableHeaders' => $tableHeaders,
                        ]) ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

