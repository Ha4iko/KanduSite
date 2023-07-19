<?php

use yii\web\View;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

/* @var $this View */
/* @var $dataProvider ArrayDataProvider */
/* @var $isAdmin bool */

$data = $dataProvider->getModels();

$tableHeaders = [];
foreach ($data as $row) {
    
    foreach ($row['columns'] ?? [] as $col) {
        $tableHeaders[$col['id']] = $col['title'];
    }

}

?>

<?php $this->beginBlock('listBracketTableTemplate'); ?>
    <div class="mb">
        <div class="bracket-table champs--lg">
            <div class="bracket-table-inner">

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
                                                <div class="table-sort__text">Player</div>
                                                <div class="table-sort__btn"></div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="table-sort">
                                                <div class="table-sort__text">class</div>
                                                <div class="table-sort__btn"></div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="table-sort">
                                                <div class="table-sort__text">Faction</div>
                                                <div class="table-sort__btn"></div>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="table-sort">
                                                <div class="table-sort__text">game world</div>
                                                <div class="table-sort__btn"></div>
                                            </div>
                                        </th>

                                        <?php foreach ($tableHeaders as $tableHeader) :
                                            if($tableHeader != 'top'):?>
                                        <th>
                                            <div class="table-sort">
                                                <div class="table-sort__text"><?= 
                                                Html::encode($tableHeader);
                                                ?></div>
                                                <div class="table-sort__btn"></div>
                                            </div>
                                        </th>
                                        <?php endif; endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    {items}                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php if ($dataProvider->pagination->totalCount > $dataProvider->pagination->limit) : ?>
    <div class="catalog-controls">
        <div></div>
        {pager}
    </div>
    <?php endif; ?>

<?php $this->endBlock(); ?>