<?php

use yii\helpers\Html;
use frontend\models\BracketTableRowForm;
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

                    <?php foreach ($tableHeaders as $tableHeader) : ?>
                        <th>
                            <div class="table-sort">
                                <div class="table-sort__text"><?= Html::encode($tableHeader) ?></div>
                                <div class="table-sort__btn"></div>
                            </div>
                        </th>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>1</td>
                        <td class="table-col--left">-</td>
                        <?php foreach ($tableHeaders as $tableHeader) : ?>
                            <td>-</td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td class="table-col--left">-</td>
                        <?php foreach ($tableHeaders as $tableHeader) : ?>
                            <td>-</td>
                        <?php endforeach; ?>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>

